<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Odp;
use App\Models\Pelanggan;
use App\Models\OdpPort;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserOdpPortController extends Controller
{
    public function monitoring()
    {
        $odps = Odp::with(['ports.pelanggan'])->get();
        return view('user.odp_port.monitoring', compact('odps'));
    }

    public function assignForm()
    {
        try {
            $odps = Odp::with('ports')->get();

            // Ambil pelanggan yang belum dipetakan ke port mana pun
            $pelanggans = Pelanggan::whereDoesntHave('odpPort')->get();

            return view('user.odp_port.assign', compact('odps', 'pelanggans'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat form assign pelanggan ke port: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman.');
        }
    }

    /**
     * Simpan pemetaan pelanggan ke port.
     */
    public function assignPelanggan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'odp_port_id'   => 'required|exists:odp_ports,id',
            'pelanggan_id'  => 'required|exists:pelanggans,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $port = OdpPort::findOrFail($request->odp_port_id);
            $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);

            // Update port: tandai sebagai terpakai dan isi pelanggan_id
            $port->update([
                'pelanggan_id' => $pelanggan->id,
                'status' => 'terpakai',
            ]);

            // Update pelanggan: isi kolom relasi ke port
            // $pelanggan->update([
            //     'odp_port_id' => $port->id,
            // ]);

            // Log::info("Pelanggan {$pelanggan->nama_pelanggan} berhasil diassign ke ODP Port ID: {$port->id}");

            return back()->with('success', 'Pelanggan berhasil di-assign ke port.');
        } catch (\Exception $e) {
            Log::error('Gagal assign pelanggan ke port: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Batalkan pemetaan pelanggan dari port (unassign).
     */
    public function unassign($id)
    {
        try {
            $port = OdpPort::with('pelanggan')->findOrFail($id);

            // Pastikan port memang sedang terpakai
            if ($port->status !== 'terpakai' || !$port->pelanggan_id) {
                return back()->with('warning', 'Port ini tidak sedang dipakai.');
            }

            // Update port: kosongkan pelanggan dan status
            $port->update([
                'pelanggan_id' => null,
                'status' => 'kosong',
            ]);

            Log::info('Berhasil unassign pelanggan dari port', ['port_id' => $id]);

            return back()->with('success', 'Pelanggan berhasil di-unassign dari port.');
        } catch (\Exception $e) {
            Log::error('Gagal unassign pelanggan dari port: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat melakukan unassign.');
        }
    }

}
