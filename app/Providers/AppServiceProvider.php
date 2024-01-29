<?php

namespace App\Providers;

use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

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
        view()->composer('*', function ($e) {
            $umum = Pengaturan::first();
            $timezone = $umum->timezone;

            if (!session()->has('zonawaktu')) {
                session(['zonawaktu' => $timezone]);
            }

            $e->with([
                'tanggal_sekarang' => Carbon::now()->timezone($timezone)->isoFormat('dddd, DD MMMM YYYY'),
                'zonawaktu' => $timezone,
                'title_web' => $umum->nama,
                'logo' => ($umum->logo === NULL || $umum->logo === '' || $umum->logo == 'logo') ? env('APP_NAME') : '<img src="' . url('/storage/foto/' . $umum->logo) . '" height="50">',
            ]);
        });
    }
}
