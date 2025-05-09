<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserBillController extends Controller
{
    /**
     * Tampilkan halaman daftar tagihan.
     */
    public function index()
    {
        try {
            $bills = Bill::with(['subscription.pelanggan', 'subscription.paket'])->latest()->get();
            $subscriptions = Subscription::with(['pelanggan', 'paket'])->get(); // Tambahan untuk form
            return view('user.bill.index', compact('bills', 'subscriptions'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data tagihan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data tagihan.');
        }
    }

    /**
     * Simpan tagihan baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_id'     => 'required|exists:subscriptions,id',
            'periode'             => 'required|date_format:Y-m',
            'jumlah_tagihan'      => 'required|numeric|min:0',
            'status_pembayaran'   => 'required|in:belum,lunas,jatuh_tempo',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $bill = Bill::create($validator->validated());

            // ✅ Tambahkan log sukses
            Log::info('Tagihan berhasil ditambahkan', [
                'bill_id' => $bill->id,
                'subscription_id' => $bill->subscription_id,
                'periode' => $bill->periode,
                'jumlah_tagihan' => $bill->jumlah_tagihan,
                'status_pembayaran' => $bill->status_pembayaran,
                'tanggal_jatuh_tempo' => $bill->tanggal_jatuh_tempo,
            ]);

            return back()->with('success', 'Tagihan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan tagihan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }


    /**
     * Perbarui data tagihan.
     */
    public function update(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'periode'             => 'required|date_format:Y-m',
            'jumlah_tagihan'      => 'required|numeric|min:0',
            'status_pembayaran'   => 'required|in:belum_dibayar,dibayar',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $bill->update($validator->validated());
            return back()->with('success', 'Data tagihan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui tagihan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Hapus data tagihan.
     */
    public function destroy($id)
    {
        try {
            $bill = Bill::findOrFail($id);
            $bill->delete();
            return back()->with('success', 'Tagihan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus tagihan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
