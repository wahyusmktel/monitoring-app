@extends('layouts.user.app')

@section('title', 'Sebaran Pelanggan')

@section('content')
<div class="page-header">
    <h4 class="page-title">Sebaran Pelanggan</h4>
    <x-breadcrumb :items="[['label' => 'Pelanggan', 'url' => route('user.pelanggan.index')], ['label' => 'Sebaran']]" />
</div>

<div class="card">
    <div class="card-body">
        <div id="map" style="height: 600px;"></div>
    </div>
</div>
@endsection

@push('script')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([-5.375, 105.079], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const pelanggans = @json($pelanggans);

    pelanggans.forEach(p => {
        if (p.latitude && p.longitude) {
            const marker = L.marker([p.latitude, p.longitude]).addTo(map);
            const info = `
                <strong>${p.nama_pelanggan}</strong><br>
                Alamat: ${p.alamat ?? '-'}<br>
                No HP: ${p.no_hp ?? '-'}<br>
                ODP: ${p.odp?.nama_odp ?? '-'}
            `;
            marker.bindPopup(info);
        }
    });
</script>
@endpush
