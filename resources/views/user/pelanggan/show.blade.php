@extends('layouts.user.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="page-header">
    <h4 class="page-title">Detail Pelanggan</h4>
    <x-breadcrumb :items="[
        ['label' => 'Pelanggan', 'url' => route('user.pelanggan.index')],
        ['label' => 'Detail', 'url' => '#']
    ]" />
</div>

<div class="card">
    <div class="card-body">
        <h5>Informasi Pelanggan</h5>
        <table class="table">
            <tr><th>Nama</th><td>{{ $pelanggan->nama_pelanggan }}</td></tr>
            <tr><th>Alamat</th><td>{{ $pelanggan->alamat }}</td></tr>
            <tr><th>No HP</th><td>{{ $pelanggan->nomor_hp }}</td></tr>
            <tr><th>ODP</th><td>{{ optional($pelanggan->odp)->nama_odp ?? '-' }}</td></tr>
            <tr><th>Koordinat</th><td>{{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}</td></tr>
        </table>

        <div id="map" style="height: 400px;"></div>

        <div class="mt-4">
            <a href="{{ route('user.pelanggan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalUpdatePelanggan">Update Data</button>
        </div>
    </div>
</div>

<!-- Modal Update -->
<div class="modal fade" id="modalUpdatePelanggan" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePelanggan" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('user.pelanggan.update', $pelanggan->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Data Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama_pelanggan" class="form-control" value="{{ $pelanggan->nama_pelanggan }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $pelanggan->alamat }}">
                    </div>
                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" name="nomor_hp" class="form-control" value="{{ $pelanggan->nomor_hp }}">
                    </div>
                    <div class="form-group">
                        <label>ODP</label>
                        <select name="odp_id" class="form-control">
                            <option value="">-- Pilih ODP --</option>
                            @foreach ($odps as $odp)
                                <option value="{{ $odp->id }}" {{ $pelanggan->odp_id == $odp->id ? 'selected' : '' }}>
                                    {{ $odp->nama_odp }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ $pelanggan->latitude }}">
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ $pelanggan->longitude }}">
                    </div>
                    <div class="form-group">
                        <div id="mapUpdate" style="height: 300px;"></div>
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

@push('script')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const lat = {{ $pelanggan->latitude ?? 0 }};
    const lng = {{ $pelanggan->longitude ?? 0 }};

    const map = L.map('map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup("{{ $pelanggan->nama_pelanggan }}")
        .openPopup();
</script>

<script>
    let mapUpdate, markerUpdate;

    $('#modalUpdatePelanggan').on('shown.bs.modal', function () {
        let lat = parseFloat($('input[name="latitude"]').val()) || -5.375;
        let lng = parseFloat($('input[name="longitude"]').val()) || 105.079;

        if (!mapUpdate) {
            mapUpdate = L.map('mapUpdate').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapUpdate);

            markerUpdate = L.marker([lat, lng], { draggable: true }).addTo(mapUpdate);

            markerUpdate.on('dragend', function (e) {
                const position = markerUpdate.getLatLng();
                $('input[name="latitude"]').val(position.lat.toFixed(15));
                $('input[name="longitude"]').val(position.lng.toFixed(15));
            });

            mapUpdate.on('click', function (e) {
                markerUpdate.setLatLng(e.latlng);
                $('input[name="latitude"]').val(e.latlng.lat.toFixed(15));
                $('input[name="longitude"]').val(e.latlng.lng.toFixed(15));
            });
        } else {
            mapUpdate.setView([lat, lng], 15);
            markerUpdate.setLatLng([lat, lng]);
        }

        setTimeout(() => {
            mapUpdate.invalidateSize();
        }, 200);
    });
</script>
@endpush
