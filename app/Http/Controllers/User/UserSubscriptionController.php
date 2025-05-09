<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Paket;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserSubscriptionController extends Controller
{
    /**
     * Tampilkan halaman daftar subscription.
     */
    public function index()
    {
        try {
            $subscriptions = Subscription::with(['pelanggan', 'paket'])->latest()->get();
            $pelanggans = Pelanggan::all(); // Ambil semua pelanggan untuk kebutuhan form
            $pakets = Paket::all(); // Jika kamu juga butuh paket untuk dropdown form

            return view('user.subscription.index', compact('subscriptions', 'pelanggans', 'pakets'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data subscription: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data subscription.');
        }
    }

    /**
     * Simpan subscription baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'paket_id' => 'required|exists:pakets,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Subscription::create($validator->validated());
            return back()->with('success', 'Subscription berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan subscription: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan subscription.');
        }
    }

    /**
     * Update subscription.
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'paket_id' => 'required|exists:pakets,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $subscription->update($validator->validated());
            return back()->with('success', 'Subscription berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update subscription: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui subscription.');
        }
    }

    /**
     * Hapus subscription.
     */
    public function destroy($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();

            return back()->with('success', 'Subscription berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus subscription: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus subscription.');
        }
    }
}
