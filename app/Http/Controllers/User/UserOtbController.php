<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Otb;
use App\Models\OltPort;
use App\DataTables\OtbDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserOtbController extends Controller
{
    /**
     * Menampilkan halaman data OTB dengan DataTable.
     */
    public function index(OtbDataTable $dataTable)
    {
        try {
            $oltPorts = OltPort::with('olt')->get();
            return $dataTable->render('user.otb.index', compact('oltPorts'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data OTB: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman OTB.');
        }
    }

    /**
     * Menyimpan data OTB baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_otb'   => 'required|string|max:255',
            'olt_port_id' => 'required|uuid|exists:olt_ports,id',
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
            Otb::create($validator->validated());
            return response()->json(['message' => 'OTB berhasil ditambahkan.']);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan OTB: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    /**
     * Memperbarui data OTB.
     */
    public function update(Request $request, $id)
    {
        $otb = Otb::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_otb'   => 'required|string|max:255',
            'olt_port_id' => 'required|uuid|exists:olt_ports,id',
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
            $otb->update($validator->validated());
            return response()->json(['message' => 'OTB berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Gagal update OTB: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }

    /**
     * Menghapus data OTB.
     */
    public function destroy($id)
    {
        try {
            $otb = Otb::findOrFail($id);
            $otb->delete();
            return response()->json(['message' => 'OTB berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus OTB: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
