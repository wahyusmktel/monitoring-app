@extends('layouts.user.app')

@section('title', 'Manajemen Paket')

@section('content')
    <div class="page-header">
        <h4 class="page-title">Manajemen Paket</h4>
        <x-breadcrumb :items="[['label' => 'Paket', 'url' => route('user.paket.index')]]" />
    </div>

    <div class="page-category">
        <div class="mb-3">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddPaket">
                <i class="fas fa-plus"></i> Tambah Paket
            </button>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Kecepatan</th>
                            <th>Harga (Rp)</th>
                            <th>Durasi (Hari)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pakets as $paket)
                            <tr>
                                <td>{{ $paket->nama_paket }}</td>
                                <td>{{ $paket->kecepatan }}</td>
                                <td>{{ number_format($paket->harga) }}</td>
                                <td>{{ $paket->durasi_hari }} hari</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#modalEditPaket{{ $paket->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('user.paket.destroy', $paket->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus paket ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEditPaket{{ $paket->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog">
                                    <form action="{{ route('user.paket.update', $paket->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Paket</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Paket</label>
                                                    <input type="text" name="nama_paket" value="{{ $paket->nama_paket }}"
                                                        class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Kecepatan</label>
                                                    <input type="text" name="kecepatan" value="{{ $paket->kecepatan }}"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Harga</label>
                                                    <input type="number" name="harga" value="{{ $paket->harga }}"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Durasi (Hari)</label>
                                                    <input type="number" name="durasi_hari"
                                                        value="{{ $paket->durasi_hari }}" class="form-control">
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada paket</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalAddPaket" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form action="{{ route('user.paket.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Paket</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Paket</label>
                            <input type="text" name="nama_paket" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kecepatan</label>
                            <input type="text" name="kecepatan" class="form-control" placeholder="Contoh: 10 Mbps">
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Durasi (Hari)</label>
                            <input type="number" name="durasi_hari" class="form-control" required>
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
