<?php

namespace App\Providers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Sale;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        Product::observe(AuditObserver::class);
        Batch::observe(AuditObserver::class);
        Sale::observe(AuditObserver::class);

        Gate::define('admin-access', function (User $user) {
        return $user->role === 'admin';
        });

        Gate::define('manager-access', function (User $user) {
        return in_array($user->role, ['admin', 'manager']);
        });
    }
}
