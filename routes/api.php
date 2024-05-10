<?php

use App\Http\Controllers\Admin\DataAnalytisController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\MotoController;
use App\Http\Controllers\Admin\MotoTypeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderDetailController;
use App\Http\Controllers\Admin\RentPackageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\MotoController as UserMotoController;
use App\Http\Controllers\User\MotoTypeController as UserMotoTypeController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('refresh', [AuthController::class, 'refeshToken'])->name('refeshToken');
    Route::post('cofirmRegister', [AuthController::class, 'cofirmRegister'])->name('cofirmRegister');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('logout');
});

Route::group(['prefix' => 'motos', 'as' => 'motos.'], function () {
    Route::get('/', [UserMotoController::class, 'index'])->name('index');
    Route::get('/{motoReadyRent}', [UserMotoController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'moto-types', 'as' => 'motoTypes.'], function () {
    Route::get('/', [UserMotoTypeController::class, 'index'])->name('index');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'orders', 'as' => 'orders.'], function () {
    Route::get('/', [UserOrderController::class, 'index'])->name('index');
    Route::post('/', [UserOrderController::class, 'store'])->name('store');
    Route::get('/{order}', [UserOrderController::class, 'show'])->name('show')->middleware('can:show,order');
    Route::patch('/{order}', [UserOrderController::class, 'update'])->name('update')->middleware('can:update,order');
});

Route::group(['middleware' => ['auth:api', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['prefix' => 'mototypes', 'as' => 'mototypes.'], function () {
        Route::get('/', [MotoTypeController::class, 'index'])->name('index');
        Route::get('/all', [MotoTypeController::class, 'all'])->name('all');
        Route::get('/public', [MotoTypeController::class, 'public'])->name('public');
        Route::post('/', [MotoTypeController::class, 'store'])->name('store');
        Route::get('/{motoType}', [MotoTypeController::class, 'show'])->name('show');
        Route::put('/{motoType}', [MotoTypeController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'motos', 'as' => 'motos.'], function () {
        Route::get('/', [MotoController::class, 'index'])->name('index');
        Route::get('/export', [MotoController::class, 'export'])->name('export');
        Route::post('/import', [MotoController::class, 'import'])->name('import');
        Route::get('/{moto}', [MotoController::class, 'show'])->name('show');
        Route::post('/', [MotoController::class, 'store'])->name('store');
        Route::patch('/{moto}', [MotoController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/{onlyUser}', [UserController::class, 'show'])->name('show');
        Route::patch('/{onlyUser}', [UserController::class, 'update'])->name('update');
        Route::post('/{onlyUser}/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');
    });

    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/{orderApprove}/complete', [OrderController::class, 'complete'])->name('complete');
        Route::post('/{order}/deposit-payment', [OrderController::class, 'depositPayment'])
            ->name('depositPayment')->middleware('can:confirmDepositPayment,order');
        Route::post('/{order}/deny', [OrderController::class, 'denyOrder'])
            ->middleware('can:deny,order')->name('denyOrder');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}', [OrderController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'order-details', 'as' => 'details.'], function () {
        Route::patch('/{orderDetail}/update-moto', [OrderDetailController::class, 'updateMoto'])->name('updateMoto');
    });

    Route::group(['prefix' => 'rent-packages', 'as' => 'rentPackages.'], function () {
        Route::get('/', [RentPackageController::class, 'index'])->name('index');
        Route::get('/all', [RentPackageController::class, 'all'])->name('all');
        Route::get('/{rentPackage}', [RentPackageController::class, 'show'])->name('show');
        Route::post('/{rentPackage}/active', [RentPackageController::class, 'active'])->name('active');
        Route::post('/', [RentPackageController::class, 'store'])->name('store');
        Route::put('/{rentPackage}', [RentPackageController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'data-analytis', 'as' => 'dataAnalytis.'], function () {
        Route::get('/revenue', [DataAnalytisController::class, 'getRevenue'])->name('revenue');
        Route::get('/yearly-revenue', [DataAnalytisController::class, 'getYearlyRevenue'])->name('yearlyRevenue');
        Route::get('/monthly-revenue', [DataAnalytisController::class, 'getMonthlyRevenue'])->name('monthlyRevenue');
        Route::get('/moto-status', [DataAnalytisController::class, 'getMotoStatus'])->name('statusMoto');
        Route::get('/moto-type', [DataAnalytisController::class, 'getMotoType'])->name('motoType');
        Route::get('/top-moto-revenue', [DataAnalytisController::class, 'getTopMotosByRevenue'])->name('getTopMotosByRevenue');
        Route::get('/top-moto-type-revenue', [DataAnalytisController::class, 'getTopMotoTypesByRevenue'])->name('getTopMotoTypesByRevenue');
    });

    Route::group(['prefix' => 'holidays', 'as' => 'holidays.'], function () {
        Route::get('/', [HolidayController::class, 'index'])->name('index');
        Route::post('/', [HolidayController::class, 'store'])->name('store');
        Route::put('/{holiday}', [HolidayController::class, 'update'])->name('update');
        Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->name('destroy');
    });
});
