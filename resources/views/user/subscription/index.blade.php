@extends('layouts.user.app')

@section('title', 'Manajemen Subscription')

@section('content')
    <div class="page-header">
        <h4 class="page-title">Manajemen Subscription</h4>
        <x-breadcrumb :items="[
            ['label' => 'Billing', 'url' => '#'],
            ['label' => 'Subscription', 'url' => route('user.subscription.index')],
        ]" />
    </div>

    <div class="page-category">
        <div class="mb-3">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAdd">
                <i class="fas fa-plus"></i> Tambah Subscription
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="subscriptionTable">
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Paket</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $sub)
                            <tr>
                                <td>{{ $sub->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $sub->paket->nama_paket }}</td>
                                <td>{{ $sub->tanggal_mulai }}</td>
                                <td>{{ $sub->tanggal_berakhir }}</td>
                                <td><span
                                        class="badge badge-{{ $sub->status == 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($sub->status) }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#modalEdit{{ $sub->id }}">Edit</button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit{{ $sub->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('user.subscription.update', $sub->id) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Subscription</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Paket</label>
                                                    <select name="paket_id" class="form-control" required>
                                                        @foreach ($pakets as $paket)
                                                            <option value="{{ $paket->id }}"
                                                                {{ $sub->paket_id == $paket->id ? 'selected' : '' }}>
                                                                {{ $paket->nama_paket }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" class="form-control"
                                                        value="{{ $sub->tanggal_mulai }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Berakhir</label>
                                                    <input type="date" name="tanggal_berakhir" class="form-control"
                                                        value="{{ $sub->tanggal_berakhir }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="aktif"
                                                            {{ $sub->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="nonaktif"
                                                            {{ $sub->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-success btn-sm">Simpan</button>
                                                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalAdd" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('user.subscription.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Tambah Subscription</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pelanggan</label>
                            <select name="pelanggan_id" class="form-control" required>
                                @foreach ($pelanggans as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Paket</label>
                            <select name="paket_id" class="form-control" required>
                                @foreach ($pakets as $paket)
                                    <option value="{{ $paket->id }}">{{ $paket->nama_paket }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success btn-sm">Simpan</button>
                        <button class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
