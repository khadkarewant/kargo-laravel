<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\Employee\RequestController as EmployeeRequestController;
use App\Http\Controllers\Manager\RequestController as ManagerRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('home');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CUSTOMER ROUTES
Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/requests', [ServiceRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [ServiceRequestController::class, 'create'])->name('requests.create');
    Route::get('/requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('requests.show');
    Route::post('/requests', [ServiceRequestController::class, 'store'])->name('requests.store');
});

// EMPLOYEE ROUTES
Route::middleware(['auth', 'verified', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('employee.dashboard');
        })->name('dashboard');

        Route::get('/requests', [EmployeeRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/{serviceRequest}', [EmployeeRequestController::class, 'show'])->name('requests.show');
        
        // save employee processing details
        Route::patch('/requests/{serviceRequest}', [EmployeeRequestController::class, 'update'])->name('requests.update');
        
        // change request status separately
        Route::patch('/requests/{serviceRequest}/status', [EmployeeRequestController::class, 'updateStatus'])->name('requests.updateStatus');

        // tracking flow after approval/revision stage        
        Route::patch('/requests/{serviceRequest}/tracking-status', [EmployeeRequestController::class, 'updateTrackingStatus'])->name('requests.updateTrackingStatus');
    });

// MANAGER ROUTES
Route::middleware(['auth', 'verified', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('dashboard');

        Route::get('/requests', [ManagerRequestController::class, 'index'])->name('requests.index');

        Route::get('/requests/{serviceRequest}', [ManagerRequestController::class, 'show'])->name('requests.show');

        Route::patch('/requests/{serviceRequest}/approve', [ManagerRequestController::class, 'approve'])->name('requests.approve');
        
        Route::patch('/requests/{serviceRequest}/revision-required', [ManagerRequestController::class, 'markRevisionRequired'])->name('requests.markRevisionRequired');
    });

require __DIR__.'/auth.php';