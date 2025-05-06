<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\DataTables\OltDataTable;

class UserOltController extends Controller
{
    /**
     * Menampilkan daftar semua OLT.
     */
    public function index(OltDataTable $dataTable)
    {
        try {
            return $dataTable->render('user.olt.index');
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan DataTable OLT: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menampilkan data.');
        }
    }

    /**
     * Menyimpan OLT baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_olt'     => 'required|string|max:255',
            'ip_address'   => 'nullable|ip',
            'lokasi'       => 'nullable|string|max:255',
            'losses'       => 'nullable|numeric',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Olt::create($validator->validated());
            return redirect()->back()->with('success', 'Data OLT berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan OLT: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Memperbarui data OLT.
     */
    public function update(Request $request, $id)
    {
        $olt = Olt::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_olt'     => 'required|string|max:255',
            'ip_address'   => 'nullable|ip',
            'lokasi'       => 'nullable|string|max:255',
            'losses'       => 'nullable|numeric',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $olt->update($validator->validated());
            return redirect()->back()->with('success', 'Data OLT berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate OLT: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    /**
     * Menghapus data OLT.
     */
    public function destroy($id)
    {
        try {
            $olt = Olt::findOrFail($id);
            $olt->delete();
            return redirect()->back()->with('success', 'Data OLT berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus OLT: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
