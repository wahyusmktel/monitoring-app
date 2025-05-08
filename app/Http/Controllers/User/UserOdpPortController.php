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
    /**
     * Tampilkan form assign pelanggan ke port ODP.
     */
    public function assignForm()
    {
        try {
            $odps = Odp::with('ports')->get();
            $pelanggans = Pelanggan::whereNull('odp_id')->get(); // hanya pelanggan belum dipetakan

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
            'odp_port_id' => 'required|exists:odp_ports,id',
            'pelanggan_id' => 'required|exists:pelanggans,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $port = OdpPort::findOrFail($request->odp_port_id);
            $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);

            $port->update([
                'pelanggan_id' => $pelanggan->id,
                'status' => 'terpakai',
            ]);

            $pelanggan->update([
                'odp_id' => $port->odp_id,
            ]);

            return back()->with('success', 'Pelanggan berhasil di-assign ke port.');
        } catch (\Exception $e) {
            Log::error('Gagal assign pelanggan ke port: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
