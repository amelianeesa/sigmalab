<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth; 

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
        \Illuminate\Support\Facades\Gate::policy(\App\Models\ParameterUji::class, \App\Policies\ParameterUjiPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Kegiatan::class, \App\Policies\KegiatanPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\HasilUji::class, \App\Policies\HasilUjiPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\RiwayatTindakLanjut::class, \App\Policies\RiwayatTindakLanjutPolicy::class);

        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (Auth::check() && in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value])) {
                $pendingPengadaan = \App\Models\PermintaanPengadaan::where('status', 'diajukan')->count();
                $view->with('pendingPengadaan', $pendingPengadaan);
            } else {
                $view->with('pendingPengadaan', 0);
            }
        });

        \Illuminate\Support\Facades\Blade::if('modul', function (string $kodeModul, string $minLevel = 'lihat') {
            return Auth::check() && app(\App\Services\PermissionService::class)->userHasAccess(Auth::user(), $kodeModul, $minLevel);
        });
    }
}