@extends('layouts.user.app')

@section('title', 'Manajemen OLT Port')

@section('content')

<div class="page-header">
    <h4 class="page-title">Manajemen OLT Port</h4>
    <x-breadcrumb :items="[
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Manajemen OLT Port', 'url' => route('user.olt_port.index')],
    ]" />
</div>

<div class="page-category">
    <div class="mb-3">
        <div class="btn-group mb-3" role="group" aria-label="Aksi">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddOltPort" title="Tambah Port">
                <i class="fas fa-plus"></i>
            </button>
            <button id="btnEditSelected" class="btn btn-warning btn-sm" title="Edit Terpilih">
                <i class="fas fa-edit"></i>
            </button>
            <button id="btnDeleteSelected" class="btn btn-danger btn-sm" title="Hapus Terpilih">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <!-- Modal Tambah -->
        <div class="modal fade" id="modalAddOltPort" tabindex="-1" role="dialog" aria-labelledby="modalAddOltPort" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formAddOltPort">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah OLT Port</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Port</label>
                                <input type="text" name="nama_port" class="form-control" required placeholder="Contoh: Port 1/1">
                            </div>
                            <div class="form-group">
                                <label>OLT</label>
                                <select name="olt_id" class="form-control" required>
                                    <option value="">-- Pilih OLT --</option>
                                    @foreach ($olts as $olt)
                                        <option value="{{ $olt->id }}">{{ $olt->nama_olt }} ({{ $olt->ip_address }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kapasitas</label>
                                <input type="number" name="kapasitas" class="form-control" placeholder="Contoh: 32" min="1">
                            </div>
                            <div class="form-group">
                                <label>Losses (dB)</label>
                                <input type="number" name="losses" step="0.01" class="form-control" placeholder="Contoh: 1.25">
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

    <!-- Tabel -->
    <div class="card">
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-bordered table-striped w-100 nowrap'], false) !!}
        </div>
    </div>
</div>

@endsection

@push('script')
    {!! $dataTable->scripts() !!}

    <script>
        // Tambah Data
        $('#formAddOltPort').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('user.olt_port.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    $('#modalAddOltPort').modal('hide');
                    $('#formAddOltPort')[0].reset();
                    $('#olt-port-table').DataTable().ajax.reload(null, false);
                    toastr.success(res.message);
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                    toastr.error(msg);
                }
            });
        });

        // Styling tombol
        $(document).ready(function () {
            let wrapper = $('#olt-port-table_wrapper');
            wrapper.find('.dt-buttons, .dataTables_filter').wrapAll(
                '<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"></div>'
            );
        });

        // Checkbox master
        $(document).on('change', '#checkAll', function () {
            $('.row-checkbox').prop('checked', this.checked);
        });

        function getSelectedIds() {
            return $('.row-checkbox:checked').map(function () {
                return $(this).val();
            }).get();
        }

        $('#btnEditSelected').on('click', function () {
            let ids = getSelectedIds();
            if (ids.length !== 1) {
                alert('Pilih satu baris untuk edit.');
                return;
            }
            console.log('Edit ID:', ids[0]);
        });

        $('#btnDeleteSelected').on('click', function () {
            let ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Pilih minimal satu baris untuk hapus.');
                return;
            }

            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.ajax({
                    url: '{{ route('user.olt_port.destroy', ['id' => '__ID__']) }}'.replace('__ID__', ids[0]),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        toastr.success(res.message);
                        $('#olt-port-table').DataTable().ajax.reload();
                    },
                    error: function () {
                        toastr.error('Gagal menghapus data');
                    }
                });
            }
        });
    </script>
@endpush
