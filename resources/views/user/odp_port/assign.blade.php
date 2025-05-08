@extends('layouts.user.app')

@section('title', 'Assign Pelanggan ke Port')

@section('content')
<div class="page-header">
    <h4 class="page-title">Assign Pelanggan ke Port</h4>
    <x-breadcrumb :items="[['label' => 'Monitoring', 'url' => '#'], ['label' => 'Assign Port']]" />
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('user.odp-port.assign') }}">
            @csrf

            <div class="form-group">
                <label>Pilih Pelanggan</label>
                <select name="pelanggan_id" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Port Kosong</label>
                <select name="odp_port_id" class="form-control" required>
                    <option value="">-- Pilih Port --</option>
                    @foreach($odps as $odp)
                        @foreach($odp->ports->where('status', 'kosong') as $port)
                            <option value="{{ $port->id }}">ODP {{ $odp->nama_odp }} - Port {{ $port->port_number }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Assign</button>
        </form>
    </div>
</div>
@endsection
