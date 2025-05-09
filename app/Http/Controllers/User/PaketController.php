<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaketController extends Controller
{
    /**
     * Tampilkan semua paket.
     */
    public function index()
    {
        try {
            $pakets = Paket::latest()->get();
            return view('user.paket.index', compact('pakets'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan daftar paket: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    /**
     * Simpan paket baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket'     => 'required|string|max:255',
            'kecepatan'      => 'required|string|max:100',
            'harga'  => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Paket::create($validator->validated());
            return back()->with('success', 'Paket berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan paket: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Perbarui data paket.
     */
    public function update(Request $request, $id)
    {
        $paket = Paket::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_paket'     => 'required|string|max:255',
            'kecepatan'      => 'required|string|max:100',
            'harga'  => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $paket->update($validator->validated());
            return back()->with('success', 'Data paket berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui paket: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Hapus paket.
     */
    public function destroy($id)
    {
        try {
            $paket = Paket::findOrFail($id);
            $paket->delete();
            return back()->with('success', 'Paket berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus paket: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
