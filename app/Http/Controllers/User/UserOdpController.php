<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Odp;
use App\Models\Odc;
use App\DataTables\OdpDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\OdpPort;
use Illuminate\Support\Facades\DB;

class UserOdpController extends Controller
{
    /**
     * Menampilkan halaman index ODP.
     */
    public function index(OdpDataTable $dataTable)
    {
        try {
            $odcs = Odc::with('otb')->get();
            return $dataTable->render('user.odp.index', compact('odcs'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data ODP: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman ODP.');
        }
    }

    /**
     * Menyimpan data ODP baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_odp'     => 'required|string|max:255',
            'odc_id'       => 'required|uuid|exists:odcs,id',
            'losses'       => 'nullable|numeric|min:0',
            'lokasi'       => 'nullable|string|max:255',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'jumlah_port'  => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Simpan ODP
            $odp = Odp::create($validator->validated());

            // Generate port otomatis
            for ($i = 1; $i <= $request->jumlah_port; $i++) {
                $odp->ports()->create([
                    'port_number'   => $i,
                    'status'        => 'kosong',
                    'pelanggan_id'  => null,
                    'keterangan'    => null,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'ODP dan port berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan ODP dan port: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }
    }

    /**
     * Memperbarui data ODP.
     */
    public function update(Request $request, $id)
    {
        $odp = Odp::with('ports')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_odp'     => 'required|string|max:255',
            'odc_id'       => 'required|uuid|exists:odcs,id',
            'losses'       => 'nullable|numeric|min:0',
            'lokasi'       => 'nullable|string|max:255',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'jumlah_port'  => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update data ODP
            $odp->update($validator->validated());

            $jumlahBaru = $request->jumlah_port;
            $jumlahLama = $odp->ports->count();

            if ($jumlahBaru > $jumlahLama) {
                // Tambahkan port baru dari jumlah lama + 1 s/d jumlah baru
                for ($i = $jumlahLama + 1; $i <= $jumlahBaru; $i++) {
                    $odp->ports()->create([
                        'port_number'   => $i,
                        'status'        => 'kosong',
                    ]);
                }
            } elseif ($jumlahBaru < $jumlahLama) {
                // Ambil port kosong paling akhir
                $portYangBisaDihapus = $odp->ports()
                    ->where('status', 'kosong')
                    ->orderByDesc('port_number')
                    ->take($jumlahLama - $jumlahBaru)
                    ->get();

                // Hapus hanya jika tidak ada pelanggan terpasang
                foreach ($portYangBisaDihapus as $port) {
                    $port->delete();
                }
            }

            DB::commit();
            return response()->json(['message' => 'ODP berhasil diperbarui dan port disesuaikan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui ODP: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }

    /**
     * Menghapus data ODP.
     */
    public function destroy($id)
    {
        try {
            $odp = Odp::findOrFail($id);
            $odp->delete();
            return response()->json(['message' => 'ODP berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus ODP: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

    public function show($id)
    {
        $odp = Odp::findOrFail($id);
        return response()->json($odp);
    }
}
