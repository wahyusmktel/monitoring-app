@extends('layouts.user.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h4 class="page-title">Dashboard</h4>
        <x-breadcrumb :items="[['label' => 'Pages', 'url' => '#'], ['label' => 'Dashboard', 'url' => route('dashboard')]]" />
    </div>

    <div class="page-category">
        Selamat datang di halaman dashboard, <strong>{{ ucfirst($role) }}</strong>!
    </div>

    <form method="POST" action="{{ route('user.logout') }}" style="margin-top: 20px;">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm">Logout</button>
    </form>
@endsection
