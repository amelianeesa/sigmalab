<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Pagination\Paginator;

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
        if (!app()->runningInConsole()) {
            \Spatie\Activitylog\Models\Activity::creating(function (\Spatie\Activitylog\Models\Activity $activity) {
                if (!app()->has('request_batch_uuid')) {
                    app()->instance('request_batch_uuid', (string) \Illuminate\Support\Str::uuid());
                }
                $activity->batch_uuid = app('request_batch_uuid');
            });
        }

        \Illuminate\Support\Facades\Gate::policy(\App\Models\ParameterUji::class, \App\Policies\ParameterUjiPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Kegiatan::class, \App\Policies\KegiatanPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\HasilUji::class, \App\Policies\HasilUjiPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\RiwayatTindakLanjut::class, \App\Policies\RiwayatTindakLanjutPolicy::class);

        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            // if (Auth::check() && in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value])) {
            //     $pendingPengadaan = \App\Models\PermintaanPengadaan::where('status', 'diajukan')->count();
            //     $view->with('pendingPengadaan', $pendingPengadaan);
            // } else {
            //     $view->with('pendingPengadaan', 0);
            // }


            $pendingPengadaan = 0;
            $unreadNotifCount = 0;
            $recentNotifs = collect();

            if (Auth::check()) {
                if (in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value])) {
                    $pendingPengadaan = \App\Models\PermintaanPengadaan::where('status', 'diajukan')->count();
                }

                $unreadNotifCount = \App\Models\Notifikasi::where('users_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

                $recentNotifs = \App\Models\Notifikasi::where('users_id', Auth::id())
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            }

            $view->with('pendingPengadaan', $pendingPengadaan);
            $view->with('unreadNotifCount', $unreadNotifCount);
            $view->with('recentNotifs', $recentNotifs);
        });

        \Illuminate\Support\Facades\Blade::if('modul', function (string $kodeModul, string $minLevel = 'lihat') {
            return Auth::check() && app(\App\Services\PermissionService::class)->userHasAccess(Auth::user(), $kodeModul, $minLevel);
        });
    }
}