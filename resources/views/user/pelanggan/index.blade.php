@extends('layouts.user.app')

@section('title', 'Manajemen Pelanggan')

@section('content')

<div class="page-header">
    <h4 class="page-title">Manajemen Pelanggan</h4>
    <x-breadcrumb :items="[
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Pelanggan', 'url' => route('user.pelanggan.index')],
    ]" />
</div>

<div class="page-category">
    <div class="mb-3">
        <div class="btn-group mb-3" role="group" aria-label="Aksi Pelanggan">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddPelanggan" title="Tambah Pelanggan">
                <i class="fas fa-plus"></i>
            </button>
            <button id="btnEditSelected" class="btn btn-warning btn-sm" title="Edit Terpilih">
                <i class="fas fa-edit"></i>
            </button>
            <button id="btnDeleteSelected" class="btn btn-danger btn-sm" title="Hapus Terpilih">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <!-- Modal Tambah Pelanggan -->
        <div class="modal fade" id="modalAddPelanggan" tabindex="-1" role="dialog" aria-labelledby="modalAddPelanggan" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formAddPelanggan">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Pelanggan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="nama_pelanggan" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>No HP</label>
                                        <input type="text" name="nomor_hp" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="text" name="latitude" class="form-control" placeholder="-5.123456">
                                    </div>
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="text" name="longitude" class="form-control" placeholder="105.123456">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pilih Port ODP Kosong</label>
                                        <select name="odp_port_id" class="form-control">
                                            <option value="">-- Pilih Port --</option>
                                            @foreach (\App\Models\OdpPort::with('odp')->where('status', 'kosong')->get() as $port)
                                                <option value="{{ $port->id }}">
                                                    {{ $port->odp->nama_odp ?? 'ODP' }} - Port {{ $port->port_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih Paket</label>
                                        <select name="paket_id" class="form-control">
                                            <option value="">-- Pilih Paket --</option>
                                            @foreach (\App\Models\Paket::orderBy('nama_paket')->get() as $paket)
                                                <option value="{{ $paket->id }}">{{ $paket->nama_paket }} - Rp {{ number_format($paket->harga, 0, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Mulai Berlangganan</label>
                                        <input type="date" name="tanggal_mulai" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {!! $dataTable->table(['class' => 'table table-striped table-bordered w-100 nowrap'], false) !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    {!! $dataTable->scripts() !!}

    <script>
        $(document).ready(function () {
            // Tambah pelanggan
            $('#formAddPelanggan').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('user.pelanggan.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function () {
                        $('#modalAddPelanggan').modal('hide');
                        $('#formAddPelanggan')[0].reset();
                        $('#pelanggan-table').DataTable().ajax.reload(null, false);
                        toastr.success('Pelanggan berhasil ditambahkan');
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                        toastr.error(msg);
                    }
                });
            });

            // Select all checkbox
            $(document).on('change', '#checkAll', function () {
                $('.row-checkbox').prop('checked', this.checked);
            });

            function getSelectedIds() {
                return $('.row-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();
            }

            $('#btnEditSelected').click(function () {
                let ids = getSelectedIds();
                if (ids.length !== 1) return alert('Pilih satu baris untuk edit.');
                console.log('Edit ID:', ids[0]); // Ganti dengan logika isi form
            });

            $('#btnDeleteSelected').click(function () {
                let ids = getSelectedIds();
                if (ids.length === 0) return alert('Pilih minimal satu baris.');

                if (confirm('Yakin ingin menghapus data terpilih?')) {
                    $.ajax({
                        url: '{{ route("user.pelanggan.massDelete") }}',
                        method: 'POST',
                        data: {
                            ids: ids,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function () {
                            toastr.success('Data berhasil dihapus');
                            $('#pelanggan-table').DataTable().ajax.reload();
                        },
                        error: function () {
                            toastr.error('Gagal menghapus data');
                        }
                    });
                }
            });

            $('#pelanggan-table_wrapper').find('.dt-buttons, .dataTables_filter').wrapAll(
                '<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"></div>'
            );
        });
    </script>
@endpush
