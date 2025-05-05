@extends('layouts.auth.app')

@section('title', 'Login')

@section('content')
    <div class="container container-login animated fadeIn">
        <h3 class="text-center">Sign In To Admin</h3>

        {{-- Tampilkan error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tampilkan notifikasi logout atau sukses --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('user.login.attempt') }}">
            @csrf
            <div class="login-form">
                <div class="form-group form-floating-label">
                    <input id="email" name="email" type="email" class="form-control input-border-bottom"
                        value="{{ old('email') }}" required>
                    <label for="email" class="placeholder">Email</label>
                </div>
                <div class="form-group form-floating-label">
                    <input id="password" name="password" type="password" class="form-control input-border-bottom" required>
                    <label for="password" class="placeholder">Password</label>
                    <div class="show-password"><i class="icon-eye"></i></div>
                </div>
                <div class="row form-sub m-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="rememberme" name="remember">
                        <label class="custom-control-label" for="rememberme">Remember Me</label>
                    </div>
                    <a href="#" class="link float-right">Forget Password ?</a>
                </div>
                <div class="form-action mb-3">
                    <button type="submit" class="btn btn-primary btn-rounded btn-login">Sign In</button>
                </div>
                <div class="login-account">
                    <span class="msg">Don't have an account yet ?</span>
                    <a href="#" id="show-signup" class="link">Sign Up</a>
                </div>
            </div>
        </form>
    </div>
@endsection
