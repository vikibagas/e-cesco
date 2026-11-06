<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MasterData\UserClientController;
use App\Http\Controllers\MasterData\UserController;
use App\Http\Controllers\MasterData\CabangController;
use App\Http\Controllers\MasterData\KustomerController;
use App\Http\Controllers\Shipments\ShipmentController;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home', [
    'content' => Lang::get('home'),
]))->name('home');

Route::get('/about', fn () => Inertia::render('About', [
    'content' => Lang::get('about'),
]))->name('about');

Route::middleware('portal.auth')->group(function (): void {
    Route::get('/e-cesco/dashboard', fn () => Inertia::render('Operations/Dashboard'))
        ->name('e-cesco.dashboard');
    Route::get('/e-cesco/shipments', [ShipmentController::class, 'index'])
        ->name('e-cesco.shipments.index');
    Route::get('/e-cesco/shipments/{shipment}/edit', [ShipmentController::class, 'edit'])
        ->name('e-cesco.shipments.edit');
    Route::put('/e-cesco/shipments/{shipment}', [ShipmentController::class, 'update'])
        ->name('e-cesco.shipments.update');
    Route::post('/e-cesco/shipments/{shipment}/cancel', [ShipmentController::class, 'cancel'])
        ->name('e-cesco.shipments.cancel');
    Route::get('/e-cesco/shipments/{shipment}/print-awb', [ShipmentController::class, 'printAwb'])
        ->name('e-cesco.shipments.print-awb');
    Route::get('/e-cesco/shipments/{shipment}/print-label', [ShipmentController::class, 'printLabel'])
        ->name('e-cesco.shipments.print-label');

    Route::prefix('/e-cesco/master-data')
        ->name('e-cesco.master-data.')
        ->middleware('portal.admin')
        ->group(function (): void {
            Route::resource('users', UserController::class)->except('show');
            Route::resource('users-client', UserClientController::class)
                ->except('show')
                ->parameters(['users-client' => 'userClient']);
            Route::resource('branches', CabangController::class)
                ->except('show')
                ->parameters(['branches' => 'cabang']);
            Route::resource('customers', KustomerController::class)
                ->except('show')
                ->parameters(['customers' => 'kustomer']);
        });
});

Route::get('/e-cesco/login', [LoginController::class, 'create'])
    ->name('login');
Route::post('/e-cesco/login', [LoginController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('portal.auth')
    ->name('logout');
