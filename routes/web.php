<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('user.login');
Route::post('login', [UserAuthController::class, 'login'])->name('user.login.attempt');
Route::post('logout', [UserAuthController::class, 'logout'])->name('user.logout');

// ✅ Route Dashboard (hanya bisa diakses jika login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->getRoleNames()->first() ?? 'User';
        return view('user.dashboard.index', compact('role'));
    })->name('dashboard');
});
