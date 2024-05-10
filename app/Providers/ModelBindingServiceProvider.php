<?php

namespace App\Providers;

use App\Enums\Order\OrderStatus;
use App\Enums\User\UserRole;
use App\Models\Moto;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModelBindingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::bind('onlyUser', function ($value) {
            return User::role([UserRole::USER])->findOrFail($value);
        });

        Route::bind('motoReadyRent', function ($value) {
            return Moto::readyRent()->findOrFail($value);
        });

        Route::bind('orderApprove', function ($value) {
            return Order::status(OrderStatus::APPROVE)->findOrFail($value);
        });
    }
}
