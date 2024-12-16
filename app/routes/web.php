<?php

use App\Events\BookingMapUpdatedEvent;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingResponderController;
use App\Http\Controllers\BrgyStaffController;
use App\Http\Controllers\ResponderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HydrantController;
use App\Models\BookingResponder;

Route::get('/', function () {
    
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])
->group(function() {
    // Dashboard
    Route::middleware(['role:admin|admin_staff|brgy_staff'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    //Station
    Route::controller(StationController::class)
    ->middleware(['role:admin|admin_staff'])
    ->prefix('/stations')
    ->group(function () {
        Route::get('/display', 'display')->name('stations.display');
        Route::get('/', 'index')->name('stations.index');
        Route::post('/', 'store')->name('stations.store');
        Route::get('/{id}', 'show')->name('stations.show');
        Route::put('/{id}', 'edit')->name('stations.edit');
        Route::delete('/{id}', 'delete')->name('stations.delete');
    });

    //AdminStaff
    Route::controller(AdminStaffController::class)
    ->prefix('/admin_staff')
    ->middleware('role:admin')
    ->group(function () {
        Route::get('/display', 'display')->name('admin_staff.display');
        Route::get('/', 'index')->name('admin_staff.index');
        Route::post('/', 'store')->name('admin_staff.store');
        Route::put('/{id}', 'edit')->name('admin_staff.edit');
        Route::delete('/{id}', 'delete')->name('admin_staff.delete');
    });

    //Brgy Staff
    Route::controller(BrgyStaffController::class)
    ->prefix('/brgy_staff')
    ->middleware('role:admin_staff')
    ->group(function () {
        Route::get('/display', 'display')->name('brgy_staff.display');
        Route::get('/', 'index')->name('brgy_staff.index');
        Route::post('/', 'store')->name('brgy_staff.store');
        Route::put('/{id}', 'edit')->name('brgy_staff.edit');
        Route::put('/{id}', 'delete')->name('brgy_staff.delete');
    });

    //Responder
    Route::controller(ResponderController::class)
    ->prefix('/responders')
    ->middleware('role:admin_staff')
    ->group(function () {
        Route::get('/display', 'display')->name('responders.display');
        Route::get('/', 'index')->name('responders.index');
        Route::post('/', 'store')->name('responders.store');
    Route::put('/{id}', 'edit')->name('responders.edit');
        Route::put('/{id}', 'delete')->name('responders.delete');
    });

    //Users
    Route::controller(UserController::class)
    ->prefix('/users')
    ->group(function () {
        Route::get('/display', 'display')->name('users.display');
        Route::get('/', 'index')->name('users.index');
        Route::post('/', 'store')->name('users.store');
        Route::get('/{id}', 'show')->name('users.show');
        Route::put('/{id}', 'edit')->name('users.edit');
        Route::delete('/{id}', 'delete')->name('users.delete');
        Route::get('/count', 'count')->name('users.count');
    });

    //Bookings
    Route::controller(BookingController::class)
    ->prefix('/bookings')
    ->group(function () {
        Route::get('/display', 'display')->name('bookings.display');
        Route::get('/', 'index')->name('bookings.index');
        Route::post('/', 'store')->name('bookings.store');
        Route::get('/show', 'show')->name('bookings.show');
        Route::get('/logs', 'logs')->name('bookings.logs');
        Route::put('/', 'edit')->name('bookings.edit');
        Route::delete('/', 'delete')->name('bookings.delete');
        Route::post('/sendcoords', 'sendCoords')->name('bookings.sendCoords');
        Route::post('/unsetrespondercoords', 'unsetResponderCoords')->name('bookings.unsetResponderCoords');
        Route::get('/counts', 'counts')->name('bookings.count');
        
    });

    //BookingResponders
    Route::controller(BookingResponderController::class)
    ->prefix('/bookingresponders')
    ->group(function () {
        Route::get('/display', 'display')->name('bookingresponders.display');
        Route::get('/', 'index')->name('bookingresponders.index');
        Route::post('/', 'store')->name('bookingresponders.store');
        Route::put('/{id}', 'edit')->name('bookingresponders.edit');
        Route::put('/', 'bulkEdit')->name('bookingresponders.bulkEdit');
        Route::delete('/{id}', 'delete')->name('bookingresponders.delete');
        Route::get('/getongoingbooking', 'getOnGoingBooking')->name('bookingresponders.getOnGoingBooking');
    });

    //Hydrants
    Route::controller(HydrantController::class)
    ->middleware(['role:admin_staff'])
    ->prefix('/hydrants')
    ->name('hydrants.')
    ->group(function () {
        Route::get('/display', 'display')->name('display');
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'edit')->name('edit');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    //Logs
    Route::controller(ActivityLogController::class)
    ->prefix('/activitylogs')
    ->group(function () {
        Route::post('/', 'store')->name('activitylogs.store');
        Route::get('/{id}', 'show')->name('activitylogs.show');
        Route::put('/{id}', 'edit')->name('activitylogs.edit');
        Route::delete('/{id}', 'delete')->name('activitylogs.delete');
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

Route::get('/send', function() {
    event(new BookingMapUpdatedEvent(25, 'test'));
});

Route::get('/phpinfo', function() {
    return phpinfo();
});

require __DIR__.'/auth.php';
