@extends('layouts.user.app')

@section('title', 'Manajemen OLT')

@section('content')

    <div class="page-header">
        <h4 class="page-title">Manajemen OLT</h4>
        <x-breadcrumb :items="[['label' => 'Pages', 'url' => '#'], ['label' => 'Manajemen OLT', 'url' => route('user.olt.index')]]" />
    </div>

    <div class="page-category">
        <div class="mb-3">
            <div class="btn-group mb-3" role="group" aria-label="Aksi OLT">
                <!-- Tambah -->
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddOlt" title="Tambah OLT">
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

            <!-- Modal Tambah OLT -->
            <div class="modal fade" id="modalAddOlt" tabindex="-1" role="dialog" aria-labelledby="modalAddOlt"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah OLT</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formAddOlt">
                                @csrf
                                <div class="form-group">
                                    <label>Nama OLT</label>
                                    <input type="text" name="nama_olt" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>IP Address</label>
                                    <input type="text" name="ip_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Losses</label>
                                    <input type="number" name="losses" step="0.01" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control">
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
            $('#formAddOlt').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('user.olt.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#modalAddOlt').modal('hide');
                        $('#formAddOlt')[0].reset();
                        $('#olt-table').DataTable().ajax.reload(null, false);
                        toastr.success('OLT berhasil ditambahkan');
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                        toastr.error(msg);
                    }
                });
            });

            // Format tampilan tombol filter dan export sejajar
            let wrapper = $('#olt-table_wrapper');
            wrapper.find('.dt-buttons, .dataTables_filter').wrapAll(
                '<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"></div>'
            );
        });

        // Checkbox master
        $(document).on('change', '#checkAll', function() {
            $('.row-checkbox').prop('checked', this.checked);
        });

        function getSelectedIds() {
            return $('.row-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        }

        $('#btnEditSelected').on('click', function() {
            let ids = getSelectedIds();
            if (ids.length !== 1) {
                alert('Pilih satu baris untuk edit.');
                return;
            }
            console.log('Edit ID:', ids[0]);
            // Lanjutkan buka modal atau fetch data
        });

        $('#btnDeleteSelected').on('click', function() {
            let ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Pilih minimal satu baris untuk hapus.');
                return;
            }
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.ajax({
                    url: '{{ route('user.olt.destroy', ['id' => '__ID__']) }}'.replace('__ID__', ids[0]),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        toastr.success('Data berhasil dihapus');
                        $('#olt-table').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Gagal menghapus data');
                    }
                });
            }
        });
    </script>
@endpush
