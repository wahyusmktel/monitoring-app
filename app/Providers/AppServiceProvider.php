<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set('Asia/Jakarta'); // native PHP timezone
        Carbon::setLocale('id'); // untuk locale bahasa (id-ID)
        Carbon::now()->setTimezone('Asia/Jakarta'); // optional redundant
    }
}
