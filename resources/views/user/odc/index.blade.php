@extends('layouts.user.app')

@section('title', 'Manajemen ODC')

@section('content')

<div class="page-header">
    <h4 class="page-title">Manajemen ODC</h4>
    <x-breadcrumb :items="[
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Manajemen ODC', 'url' => route('user.odc.index')],
    ]" />
</div>

<div class="page-category">
    <div class="mb-3">
        <div class="btn-group mb-3" role="group" aria-label="Aksi">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddOdc" title="Tambah ODC">
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
        <div class="modal fade" id="modalAddOdc" tabindex="-1" role="dialog" aria-labelledby="modalAddOdc" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formAddOdc">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah ODC</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama ODC</label>
                                <input type="text" name="nama_odc" class="form-control" required placeholder="Contoh: ODC-XX-01">
                            </div>
                            <div class="form-group">
                                <label>OTB</label>
                                <select name="otb_id" class="form-control" required>
                                    <option value="">-- Pilih OTB --</option>
                                    @foreach ($otbs as $otb)
                                        <option value="{{ $otb->id }}">{{ $otb->nama_otb }} - {{ $otb->lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Losses (dB)</label>
                                <input type="number" name="losses" step="0.01" class="form-control" placeholder="Contoh: 1.20">
                            </div>
                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Dekat PLN, Tiang 5">
                            </div>
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="number" name="latitude" step="0.00000000000000001" class="form-control" placeholder="Contoh: -5.123456">
                            </div>
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="number" name="longitude" step="0.00000000000000001" class="form-control" placeholder="Contoh: 105.123456">
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
        // Simpan Data
        $('#formAddOdc').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('user.odc.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    $('#modalAddOdc').modal('hide');
                    $('#formAddOdc')[0].reset();
                    $('#odc-table').DataTable().ajax.reload(null, false);
                    toastr.success(res.message);
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                    toastr.error(msg);
                }
            });
        });

        // Styling
        $(document).ready(function () {
            let wrapper = $('#odc-table_wrapper');
            wrapper.find('.dt-buttons, .dataTables_filter').wrapAll(
                '<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"></div>'
            );
        });

        // Checkbox Master
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
                    url: '{{ route('user.odc.destroy', ['id' => '__ID__']) }}'.replace('__ID__', ids[0]),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        toastr.success(res.message);
                        $('#odc-table').DataTable().ajax.reload();
                    },
                    error: function () {
                        toastr.error('Gagal menghapus data');
                    }
                });
            }
        });
    </script>
@endpush
