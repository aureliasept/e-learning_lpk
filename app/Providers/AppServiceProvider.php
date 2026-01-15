<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; // <-- Tambahkan ini

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
        // Daftarkan komponen admin-layout secara manual
        // 'admin-layout' adalah nama tag <x-admin-layout>
        // 'admin.layouts.app' adalah lokasi file views/admin/layouts/app.blade.php
        Blade::component('admin-layout', 'admin.layouts.app');
    }
}