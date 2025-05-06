<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserServerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserAuthController::class, 'login'])->name('user.login.attempt');
Route::post('logout', [UserAuthController::class, 'logout'])->name('user.logout');

// ✅ Route Dashboard (hanya bisa diakses jika login)
Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('server')->group(function () {
        Route::get('/', [UserServerController::class, 'index'])->name('server.index');
        Route::post('/', [UserServerController::class, 'store'])->name('server.store');
        Route::put('/{id}', [UserServerController::class, 'update'])->name('server.update');
        Route::delete('/{id}', [UserServerController::class, 'destroy'])->name('server.destroy');
        Route::post('/delete-massal', [UserServerController::class, 'massDelete'])->name('server.massDelete');
    });
});
