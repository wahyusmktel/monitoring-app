<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Odp;
use App\Models\Odc;
use App\DataTables\OdpDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            'nama_odp'   => 'required|string|max:255',
            'odc_id'     => 'required|uuid|exists:odcs,id',
            'losses'     => 'nullable|numeric|min:0',
            'lokasi'     => 'nullable|string|max:255',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Odp::create($validator->validated());
            return response()->json(['message' => 'ODP berhasil ditambahkan.']);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan ODP: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    /**
     * Memperbarui data ODP.
     */
    public function update(Request $request, $id)
    {
        $odp = Odp::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_odp'   => 'required|string|max:255',
            'odc_id'     => 'required|uuid|exists:odcs,id',
            'losses'     => 'nullable|numeric|min:0',
            'lokasi'     => 'nullable|string|max:255',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $odp->update($validator->validated());
            return response()->json(['message' => 'ODP berhasil diperbarui.']);
        } catch (\Exception $e) {
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
}
