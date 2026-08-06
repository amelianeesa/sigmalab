<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Gate::policy(\App\Models\ParameterUji::class, \App\Policies\ParameterUjiPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Kegiatan::class, \App\Policies\KegiatanPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\HasilUji::class, \App\Policies\HasilUjiPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\RiwayatTindakLanjut::class, \App\Policies\RiwayatTindakLanjutPolicy::class);
    }
}
