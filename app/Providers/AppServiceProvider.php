<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(255);
        // định nghĩa 1 Gate để kiểm tra quyền (dùng trong Controller)
        // $user: laravel tự động truyền vào
        Gate::define('modules', function ($user, $permissionName) {
            if ($user->publish == 0 || $user->publish == -1) {
                return false;
            }
            if ($user->hasPermission($permissionName)) {
                return true;
            }
            return false;
        });
    }
}
