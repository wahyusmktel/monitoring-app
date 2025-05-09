<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserServerController;
use App\Http\Controllers\User\UserOltController;
use App\Http\Controllers\User\UserOltPortController;
use App\Http\Controllers\User\UserOtbController;
use App\Http\Controllers\User\UserOdcController;
use App\Http\Controllers\User\UserOdpController;
use App\Http\Controllers\User\UserPelangganController;
use App\Http\Controllers\User\UserOdpPortController;
use App\Http\Controllers\User\PaketController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\User\UserBillController;

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

    Route::prefix('master_data')->group(function () {
        Route::prefix('olt')->group(function () {
            Route::get('/olt', [UserOltController::class, 'index'])->name('user.olt.index');
            Route::post('/olt/store', [UserOltController::class, 'store'])->name('user.olt.store');
            Route::put('/olt/update/{id}', [UserOltController::class, 'update'])->name('user.olt.update');
            Route::delete('/olt/delete/{id}', [UserOltController::class, 'destroy'])->name('user.olt.destroy');
        });
        Route::prefix('olt_port')->group(function () {
            Route::get('/', [UserOltPortController::class, 'index'])->name('user.olt_port.index');
            Route::post('/store', [UserOltPortController::class, 'store'])->name('user.olt_port.store');
            Route::put('/update/{id}', [UserOltPortController::class, 'update'])->name('user.olt_port.update');
            Route::delete('/delete/{id}', [UserOltPortController::class, 'destroy'])->name('user.olt_port.destroy');
        });
        Route::prefix('otb')->group(function () {
            Route::get('/', [UserOtbController::class, 'index'])->name('user.otb.index');
            Route::post('/store', [UserOtbController::class, 'store'])->name('user.otb.store');
            Route::put('/update/{id}', [UserOtbController::class, 'update'])->name('user.otb.update');
            Route::delete('/delete/{id}', [UserOtbController::class, 'destroy'])->name('user.otb.destroy');
        });
        Route::prefix('odc')->group(function () {
            Route::get('/', [UserOdcController::class, 'index'])->name('user.odc.index');
            Route::post('/store', [UserOdcController::class, 'store'])->name('user.odc.store');
            Route::put('/update/{id}', [UserOdcController::class, 'update'])->name('user.odc.update');
            Route::delete('/delete/{id}', [UserOdcController::class, 'destroy'])->name('user.odc.destroy');
        });
        Route::prefix('odp')->name('user.odp.')->controller(UserOdpController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::get('/{id}', 'show')->name('show');
        });
        Route::prefix('pelanggan')->name('user.pelanggan.')->controller(UserPelangganController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::post('/mass-delete', 'massDelete')->name('massDelete');
            Route::get('/{id}/show', 'show')->name('show');
            Route::get('/sebaran-pelanggan', 'map')->name('map');
            Route::get('/sebaran-odp', 'sebaran')->name('sebaran-odp');
        });
        Route::prefix('odp-port')->name('user.odp-port.')->controller(UserOdpPortController::class)->group(function () {
            Route::get('/monitoring-port', 'monitoring')->name('monitoring');
            Route::get('/assign', 'assignForm')->name('assign.form');
            Route::post('/assign', 'assignPelanggan')->name('assign');
        });
        Route::prefix('paket')->name('user.paket.')->controller(PaketController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        });

        Route::prefix('billing')->name('user.subscription.')->controller(UserSubscriptionController::class)->group(function () {
            Route::get('/subscription', 'index')->name('index');
            Route::post('/subscription/store', 'store')->name('store');
            Route::put('/subscription/update/{id}', 'update')->name('update');
            Route::delete('/subscription/delete/{id}', 'destroy')->name('destroy');
        });

        Route::prefix('billing')->name('user.bill.')->controller(UserBillController::class)->group(function () {
            Route::get('/bill', 'index')->name('index');
            Route::post('/bill/store', 'store')->name('store');
            Route::put('/bill/update/{id}', 'update')->name('update');
            Route::delete('/bill/delete/{id}', 'destroy')->name('destroy');
        });
    });
});
