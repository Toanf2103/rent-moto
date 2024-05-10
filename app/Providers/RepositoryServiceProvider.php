<?php

namespace App\Providers;

use App\Repositories\Holiday\HolidayRepository;
use App\Repositories\Holiday\HolidayRepositoryInterface;
use App\Repositories\Moto\MotoRepository;
use App\Repositories\Moto\MotoRepositoryInterface;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Image\ImageRepositoryInterface;
use App\Repositories\MotoType\MotoTypeRepository;
use App\Repositories\MotoType\MotoTypeRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use App\Repositories\OrderHoliday\OrderHolidayRepository;
use App\Repositories\OrderHoliday\OrderHolidayRepositoryInterface;
use App\Repositories\RentPackage\RentPackageRepository;
use App\Repositories\RentPackage\RentPackageRepositoryInterface;
use App\Repositories\RentPackageDetail\RentPackageDetailRepository;
use App\Repositories\RentPackageDetail\RentPackageDetailRepositoryInterface;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MotoTypeRepositoryInterface::class, MotoTypeRepository::class);
        $this->app->bind(MotoRepositoryInterface::class, MotoRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderDetailRepositoryInterface::class, OrderDetailRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(RentPackageRepositoryInterface::class, RentPackageRepository::class);
        $this->app->bind(RentPackageDetailRepositoryInterface::class, RentPackageDetailRepository::class);
        $this->app->bind(HolidayRepositoryInterface::class, HolidayRepository::class);
        $this->app->bind(OrderHolidayRepositoryInterface::class, OrderHolidayRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
