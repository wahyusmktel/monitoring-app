@extends('layouts.user.app')

@section('title', 'Manajemen Pembayaran')

@section('content')
    <div class="page-header">
        <h4 class="page-title">Manajemen Pembayaran</h4>
        <x-breadcrumb :items="[['label' => 'Pembayaran', 'url' => route('user.payment.index')]]" />
    </div>

    <div class="page-category">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#modalAddPayment">
                    <i class="fas fa-plus"></i> Tambah Pembayaran
                </button>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Periode</th>
                            <th>Jumlah Bayar</th>
                            <th>Tanggal Bayar</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->bill->subscription->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $payment->bill->periode }}</td>
                                <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>{{ $payment->tanggal_bayar }}</td>
                                <td>{{ ucfirst($payment->metode_pembayaran) }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm">Detail</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalAddPayment" tabindex="-1" role="dialog" aria-labelledby="modalAddPayment" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('user.payment.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Tagihan</label>
                            <select name="bill_id" class="form-control" required>
                                @foreach ($bills as $bill)
                                    <option value="{{ $bill->id }}">
                                        {{ $bill->subscription->pelanggan->nama_pelanggan }} - Periode {{ $bill->periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Bayar</label>
                            <input type="number" name="jumlah_bayar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-control" required>
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection