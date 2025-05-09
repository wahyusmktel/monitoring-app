<?php

namespace App\Http\Controllers\User;

use App\DataTables\PelangganDataTable;
use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Odp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\OdpPort;
use App\Models\Paket;
use App\Models\Subscription;

class UserPelangganController extends Controller
{
    // Tampilkan data pelanggan
    public function index(PelangganDataTable $dataTable)
    {
        try {
            $odps = Odp::orderBy('nama_odp')->get(); // ✅ Untuk dropdown ODP di form
            return $dataTable->render('user.pelanggan.index', compact('odps'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman.');
        }
    }

    // Simpan data pelanggan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan'   => 'required|string|max:255',
            'alamat'           => 'nullable|string|max:500',
            'nomor_hp'         => 'nullable|string|max:20',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'odp_port_id'      => 'nullable|exists:odp_ports,id',
            'paket_id'         => 'nullable|exists:pakets,id',
            'tanggal_mulai'    => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Simpan pelanggan
            $pelanggan = Pelanggan::create($validator->safe()->only([
                'nama_pelanggan', 'alamat', 'nomor_hp', 'latitude', 'longitude'
            ]));

            // Assign ke port ODP
            if ($request->filled('odp_port_id')) {
                $port = OdpPort::where('id', $request->odp_port_id)->where('status', 'kosong')->first();
                if ($port) {
                    $port->update([
                        'pelanggan_id' => $pelanggan->id,
                        'status'       => 'terpakai',
                    ]);
                }
            }

            // Langsung buat subscription
            if ($request->filled('paket_id') && $request->filled('tanggal_mulai')) {
                Subscription::create([
                    'pelanggan_id'   => $pelanggan->id,
                    'paket_id'       => $request->paket_id,
                    'tanggal_mulai'  => $request->tanggal_mulai,
                ]);
            }

            return back()->with('success', 'Pelanggan berhasil ditambahkan dan dipetakan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    // Perbarui data pelanggan
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'alamat'         => 'nullable|string',
            'nomor_hp'       => 'nullable|string|max:20',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'odp_port_id'    => 'nullable|exists:odp_ports,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $pelanggan->update($validator->validated());

            // Update port jika diubah
            if ($request->filled('odp_port_id')) {
                $port = OdpPort::where('id', $request->odp_port_id)->where(function ($q) use ($pelanggan) {
                    $q->where('status', 'kosong')->orWhere('pelanggan_id', $pelanggan->id);
                })->first();

                if ($port) {
                    // Kosongkan port sebelumnya
                    OdpPort::where('pelanggan_id', $pelanggan->id)->update([
                        'pelanggan_id' => null,
                        'status' => 'kosong'
                    ]);

                    $port->update([
                        'pelanggan_id' => $pelanggan->id,
                        'status'       => 'terpakai',
                    ]);
                }
            }

            return back()->with('success', 'Data pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    // Hapus data pelanggan
    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete();
            return back()->with('success', 'Pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function show($id)
    {
        try {
            $odps = Odp::all();
            $pelanggan = Pelanggan::with(['odpPort.odp'])->findOrFail($id);
            return view('user.pelanggan.show', compact('pelanggan', 'odps'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan detail pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Data pelanggan tidak ditemukan.');
        }
    }

    public function map()
    {
        $pelanggans = Pelanggan::with('odp')->whereNotNull('latitude')->whereNotNull('longitude')->get();
        return view('user.pelanggan.map', compact('pelanggans'));
    }

    public function sebaran()
    {
        try {
            $odps = Odp::select('id', 'nama_odp', 'latitude', 'longitude')->get();
            return view('user.odp.sebaran', compact('odps'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan sebaran ODP: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat halaman sebaran ODP.');
        }
    }

}
