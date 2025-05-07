<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Odc;
use App\Models\Otb;
use App\DataTables\OdcDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserOdcController extends Controller
{
    /**
     * Tampilkan halaman index dengan DataTable.
     */
    public function index(OdcDataTable $dataTable)
    {
        try {
            $otbs = Otb::with('oltPort.olt')->get();
            return $dataTable->render('user.odc.index', compact('otbs'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data ODC: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman ODC.');
        }
    }

    /**
     * Simpan data ODC baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_odc'   => 'required|string|max:255',
            'otb_id'     => 'required|uuid|exists:otbs,id',
            'losses'     => 'nullable|numeric|min:0',
            'lokasi'     => 'nullable|string|max:255',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            Odc::create($validator->validated());
            return response()->json(['message' => 'ODC berhasil ditambahkan.']);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan ODC: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    /**
     * Perbarui data ODC.
     */
    public function update(Request $request, $id)
    {
        $odc = Odc::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_odc'   => 'required|string|max:255',
            'otb_id'     => 'required|uuid|exists:otbs,id',
            'losses'     => 'nullable|numeric|min:0',
            'lokasi'     => 'nullable|string|max:255',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            $odc->update($validator->validated());
            return response()->json(['message' => 'ODC berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui ODC: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }

    /**
     * Hapus data ODC.
     */
    public function destroy($id)
    {
        try {
            $odc = Odc::findOrFail($id);
            $odc->delete();
            return response()->json(['message' => 'ODC berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus ODC: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
