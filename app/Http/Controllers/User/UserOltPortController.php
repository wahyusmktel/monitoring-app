<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\DataTables\OltPortDataTable;
use App\Models\OltPort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Olt;

class UserOltPortController extends Controller
{
    /**
     * Tampilkan data OLT Port dalam bentuk DataTable.
     */
    public function index(OltPortDataTable $dataTable)
    {
        try {
            $olts = Olt::latest()->get(); // ambil semua data OLT
            return $dataTable->render('user.olt_port.index', compact('olts'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data OLT Port: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman.');
        }
    }

    /**
     * Simpan data OLT Port baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_port' => 'required|string|max:255',
            'olt_id' => 'required|uuid|exists:olts,id',
            'status' => 'required|in:aktif,nonaktif',
            'kapasitas' => 'nullable|integer|min:1',
            'losses' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            OltPort::create($validator->validated());
            return response()->json(['message' => 'Data OLT Port berhasil ditambahkan.']);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan OLT Port: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    /**
     * Perbarui data OLT Port.
     */
    public function update(Request $request, $id)
    {
        $oltPort = OltPort::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_port' => 'required|string|max:255',
            'olt_id' => 'required|uuid|exists:olts,id',
            'status' => 'required|in:aktif,nonaktif',
            'kapasitas' => 'nullable|integer|min:1',
            'losses' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            $oltPort->update($validator->validated());
            return response()->json(['message' => 'Data OLT Port berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui OLT Port: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }

    /**
     * Hapus OLT Port.
     */
    public function destroy($id)
    {
        try {
            $oltPort = OltPort::findOrFail($id);
            $oltPort->delete();
            return response()->json(['message' => 'Data OLT Port berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus OLT Port: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
