@extends('layouts.user.app')

@section('title', 'Monitoring Port ODP')

@section('content')
<div class="page-header">
    <h4 class="page-title">Monitoring Penggunaan Port ODP</h4>
</div>

@foreach($odps as $odp)
    <div class="card mb-4">
        <div class="card-header">
            <h5>ODP: {{ $odp->nama_odp }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($odp->ports as $port)
                <div class="col-sm-6 col-lg-3">
                    <div class="card p-3">
                        <div class="d-flex align-items-center">
                            <span class="stamp stamp-md mr-3 
                                {{ $port->status == 'terpakai' ? 'bg-danger' : 'bg-success' }}">
                                <i class="fas fas fa-bullseye"></i>
                            </span>
                            <div>
                                <h5 class="mb-1">
                                    <b>Port {{ $port->port_number }}</b>
                                </h5>
                                <small class="text-muted">
                                    Status: <strong>{{ ucfirst($port->status) }}</strong><br>
                                    @if($port->pelanggan)
                                        {{ $port->pelanggan->nama_pelanggan }}
                                    @else
                                        <em>Belum terhubung</em>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>                
                @endforeach
            </div>
        </div>
    </div>
@endforeach
@endsection
