<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserPaymentController extends Controller
{
    /**
     * Tampilkan halaman daftar pembayaran.
     */
    public function index()
    {
        try {
            $payments = Payment::with(['bill.subscription.pelanggan'])->latest()->get();
            $bills = Bill::with('subscription.pelanggan')->get(); // untuk form tambah/edit
            return view('user.payment.index', compact('payments', 'bills'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan data pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data pembayaran.');
        }
    }

    /**
     * Simpan pembayaran baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_id'            => 'required|exists:bills,id',
            'jumlah_bayar'       => 'required|numeric|min:0',
            'tanggal_bayar'      => 'required|date',
            'metode_pembayaran'  => 'required|string|max:50',
            'catatan'            => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi gagal saat menambahkan pembayaran.', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);

            return back()->withErrors($validator)->withInput();
        }

        try {
            $payment = Payment::create($validator->validated());

            Log::info('Pembayaran baru berhasil disimpan.', [
                'payment_id' => $payment->id,
                'data' => $payment->toArray()
            ]);

            return back()->with('success', 'Pembayaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pembayaran: ' . $e->getMessage(), [
                'input' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Perbarui data pembayaran.
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jumlah_bayar'       => 'required|numeric|min:0',
            'tanggal_bayar'      => 'required|date',
            'metode_pembayaran'  => 'required|string|max:50',
            'catatan'            => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $payment->update($validator->validated());
            return back()->with('success', 'Data pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Hapus pembayaran.
     */
    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();
            return back()->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
