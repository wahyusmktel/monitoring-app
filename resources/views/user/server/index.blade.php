@extends('layouts.user.app')

@section('title', 'Manajemen Server')

@section('content')

    <div class="page-header">
        <h4 class="page-title">Manajemen Server</h4>
        <x-breadcrumb :items="[
            ['label' => 'Pages', 'url' => '#'],
            ['label' => 'Manajemen Server', 'url' => route('server.index')],
        ]" />
    </div>

    <div class="page-category">
        <div class="mb-3">
            <div class="btn-group mb-3" role="group" aria-label="Aksi Server">
                <!-- Tambah -->
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddServer" title="Tambah Server">
                    <i class="fas fa-plus"></i>
                </button>

                <!-- Edit -->
                <button id="btnEditSelected" class="btn btn-warning btn-sm" title="Edit Terpilih">
                    <i class="fas fa-edit"></i>
                </button>

                <!-- Hapus -->
                <button id="btnDeleteSelected" class="btn btn-danger btn-sm" title="Hapus Terpilih">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>

            <!-- Modal Tambah Server -->
            <div class="modal fade" id="modalAddServer" tabindex="-1" role="dialog" aria-labelledby="modalAddServer"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Server</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formAddServer">
                                @csrf
                                <div class="form-group">
                                    <label>Nama Server</label>
                                    <input type="text" name="nama_server" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Lokasi Server</label>
                                    <input type="text" name="lokasi_server" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Server</label>
                                    <textarea name="alamat_server" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>IP Address</label>
                                    <input type="text" name="ip_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>OS</label>
                                    <input type="text" name="operating_system" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Server</label>
                                    <input type="text" name="jenis_server" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Pemilik / User</label>
                                    <select name="id_user" class="form-control" required>
                                        <option value="">-- Pilih User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
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
        </div>
        <div class="card">
            <div class="card-body">
                {!! $dataTable->table(['class' => 'table table-striped table-bordered w-100 nowrap'], false) !!}
            </div>
        </div>
    </div>

@endsection

@push('script')
    {!! $dataTable->scripts() !!}

    <script>
        $(document).ready(function() {
            $('#formAddServer').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('server.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#modalAddServer').modal('hide');
                        $('#formAddServer')[0].reset();
                        $('#server-table').DataTable().ajax.reload(null, false);
                        toastr.success('Server berhasil ditambahkan');
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                        toastr.error(msg);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let wrapper = $('#server-table_wrapper');
            wrapper.find('.dt-buttons, .dataTables_filter').wrapAll(
                '<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"></div>');
        });
    </script>

    <script>
        // Select all
        $(document).on('change', '#checkAll', function() {
            $('.row-checkbox').prop('checked', this.checked);
        });

        // Ambil ID terpilih
        function getSelectedIds() {
            return $('.row-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        }

        // Contoh: Aksi Edit
        $('#btnEditSelected').on('click', function() {
            let ids = getSelectedIds();
            if (ids.length !== 1) {
                alert('Pilih satu baris untuk edit.');
                return;
            }

            // Buka modal edit dengan data id terpilih
            let id = ids[0];
            // Lakukan fetch data server[id], lalu isi form edit
            console.log('Edit ID:', id);
        });

        // Contoh: Aksi Hapus
        $('#btnDeleteSelected').on('click', function() {
            let ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Pilih minimal satu baris untuk hapus.');
                return;
            }

            if (confirm('Yakin ingin menghapus data terpilih?')) {
                // Kirim ajax ke route hapus massal
                $.ajax({
                    url: '{{ route('server.massDelete') }}',
                    method: 'POST',
                    data: {
                        ids: ids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        toastr.success('Data berhasil dihapus');
                        $('#server-table').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Gagal menghapus data');
                    }
                });
            }
        });
    </script>
@endpush
