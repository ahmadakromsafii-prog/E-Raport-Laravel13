<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Seluruh paginator menggunakan markup Bootstrap 5, bukan Tailwind bawaan Laravel.
        Paginator::useBootstrapFive();
    }
}
