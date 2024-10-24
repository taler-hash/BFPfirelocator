<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\BrgyStaffController;
use App\Http\Controllers\ResponderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StationController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])
->group(function() {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Station
    Route::controller(StationController::class)
    ->prefix('/stations')
    ->group(function () {
        Route::get('/display', 'display')->name('stations.display');
        Route::get('/', 'index')->name('stations.index');
        Route::get('/{id}/users', 'users')->name('stations.users');
        Route::post('/', 'store')->name('stations.store');
        Route::get('/{id}', 'show')->name('stations.show');
        Route::put('/{id}', 'edit')->name('stations.edit');
        Route::put('/{id}', 'delete')->name('stations.delete');
    });

    //AdminStaff
    Route::controller(AdminStaffController::class)
    ->prefix('/adminstaff')
    ->group(function () {
        Route::get('/display', 'display')->name('adminstaff.display');
        Route::get('/', 'index')->name('adminstaff.index');
        Route::post('/', 'store')->name('adminstaff.store');
        Route::put('/{id}', 'edit')->name('adminstaff.edit');
        Route::put('/{id}', 'delete')->name('adminstaff.delete');
    });

    //Brgy Staff
    Route::controller(BrgyStaffController::class)
    ->prefix('/brgystaff')
    ->group(function () {
        Route::get('/display', 'display')->name('brgystaff.display');
        Route::get('/', 'index')->name('brgystaff.index');
        Route::post('/', 'store')->name('brgystaff.store');
        Route::put('/{id}', 'edit')->name('brgystaff.edit');
        Route::put('/{id}', 'delete')->name('brgystaff.delete');
    });

    //Responder
    Route::controller(ResponderController::class)
    ->prefix('/responders')
    ->group(function () {
        Route::get('/display', 'display')->name('responders.display');
        Route::get('/', 'index')->name('responders.index');
        Route::post('/', 'store')->name('responders.store');
        Route::put('/{id}', 'edit')->name('responders.edit');
        Route::put('/{id}', 'delete')->name('responders.delete');
    });

    //Roles
    Route::controller(RoleController::class)
    ->prefix('/roles')
    ->group(function () {
        Route::get('/', 'index')->name('roles.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
