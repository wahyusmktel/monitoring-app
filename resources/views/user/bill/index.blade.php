@extends('layouts.user.app')

@section('title', 'Manajemen Tagihan')

@section('content')
    <div class="page-header">
        <h4 class="page-title">Manajemen Tagihan</h4>
        <x-breadcrumb :items="[['label' => 'Tagihan', 'url' => route('user.bill.index')]]" />
    </div>

    <div class="page-category">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#modalAddBill">
                    <i class="fas fa-plus"></i> Tambah Tagihan
                </button>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Periode</th>
                            <th>Jumlah Tagihan</th>
                            <th>Status</th>
                            <th>Jatuh Tempo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $bill)
                            <tr>
                                <td>{{ $bill->subscription->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $bill->subscription->paket->nama_paket }}</td>
                                <td>{{ \Carbon\Carbon::parse($bill->periode)->translatedFormat('F Y') }}</td>
                                <td>Rp {{ number_format($bill->jumlah_tagihan, 0, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $bill->status_pembayaran === 'dibayar' ? 'success' : 'warning' }}">
                                        {{ ucfirst(str_replace('_', ' ', $bill->status_pembayaran)) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($bill->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">Detail</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalAddBill" tabindex="-1" role="dialog" aria-labelledby="modalAddBill"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('user.bill.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Tagihan</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Subscription</label>
                            <select name="subscription_id" class="form-control" required>
                                @foreach ($subscriptions as $sub)
                                    <option value="{{ $sub->id }}">
                                        {{ $sub->pelanggan->nama_pelanggan }} - {{ $sub->paket->nama_paket }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Periode</label>
                            <input type="month" name="periode" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Tagihan (Rp)</label>
                            <input type="number" name="jumlah_tagihan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Status Pembayaran</label>
                            <select name="status_pembayaran" class="form-control" required>
                                <option value="belum">Belum Dibayar</option>
                                <option value="lunas">Dibayar</option>
                                <option value="jatuh_tempo">Jatuh Tempo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
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
