<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Server;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\DataTables\ServerDataTable;
use App\Models\User;

class UserServerController extends Controller
{
    public function index(ServerDataTable $dataTable)
    {
        $users = User::all(); // atau pakai where role jika hanya ingin tertentu
        return $dataTable->render('user.server.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_server' => 'required|string|max:255',
            'lokasi_server' => 'required|string|max:255',
            'alamat_server' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'operating_system' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:aktif,nonaktif',
            'jenis_server' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'id_user' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Server::create($validator->validated());
            return redirect()->back()->with('success', 'Server berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan server: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(Request $request, $id)
    {
        $server = Server::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_server' => 'required|string|max:255',
            'lokasi_server' => 'required|string|max:255',
            'alamat_server' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'operating_system' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:aktif,nonaktif',
            'jenis_server' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'id_user' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $server->update($validator->validated());
            return redirect()->back()->with('success', 'Server berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate server: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function destroy($id)
    {
        try {
            $server = Server::findOrFail($id);
            $server->delete();
            return redirect()->back()->with('success', 'Server berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus server: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function massDelete(Request $request)
    {
        try {
            Server::whereIn('id', $request->ids)->delete();
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Mass delete failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
