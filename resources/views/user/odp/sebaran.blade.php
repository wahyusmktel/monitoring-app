@extends('layouts.user.app')

@section('title', 'Sebaran ODP')

@section('content')
<div class="page-header">
    <h4 class="page-title">Sebaran ODP</h4>
    <x-breadcrumb :items="[
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Sebaran ODP', 'url' => route('user.pelanggan.sebaran-odp')],
    ]" />
</div>

<div class="card">
    <div class="card-body">
        <div id="map" style="height: 500px;"></div>
    </div>
</div>
@endsection

@push('script')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([-5.375, 105.08], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const odpIcon = L.icon({
        iconUrl: '{{ asset('icons/wifi-symbol-clipart-lg.png') }}', // ganti path jika dari CDN
        iconSize: [30, 30], // ukuran ikon
        iconAnchor: [15, 30], // posisi anchor ikon
        popupAnchor: [0, -30] // posisi popup terhadap ikon
    });

    const odps = @json($odps);

    odps.forEach(odp => {
        if (odp.latitude && odp.longitude) {
            L.marker([odp.latitude, odp.longitude], { icon: odpIcon })
                .addTo(map)
                .bindPopup(`<b>${odp.nama_odp}</b><br>Lat: ${odp.latitude}<br>Lng: ${odp.longitude}`);
        }
    });
</script>
@endpush
