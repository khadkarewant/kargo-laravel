<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\Employee\RequestController as EmployeeRequestController;
use App\Http\Controllers\Manager\RequestController as ManagerRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/employee/dashboard', function () {
    return view('employee.dashboard');
})->middleware(['auth', 'verified'])->name('employee.dashboard');

Route::get('/manager/dashboard', function () {
    return view('manager.dashboard');
})->middleware(['auth', 'verified'])->name('manager.dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/requests', [ServiceRequestController::class, 'index'])->name('requests.index');
    Route::post('/requests', [ServiceRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/create', [ServiceRequestController::class, 'create'])->name('requests.create');
    Route::get('/requests/{serviceRequest}/edit', [ServiceRequestController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('requests.update');
    Route::delete('requests/{serviceRequest}', [ServiceRequestController::class,'destroy'])->name('requests.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/employee/requests', [EmployeeRequestController::class,'index'])->name('employee.requests.index');
    Route::patch('/employee/requests/{serviceRequest}/status', [EmployeeRequestController::class, 'updateStatus'])->name('employee.requests.updateStatus');
    Route::patch('/employee/requests/{serviceRequest}/tracking-status', [EmployeeRequestController::class, 'updateTrackingStatus'])->name('employee.requests.updateTrackingStatus');

    Route::get('/manager/requests', [ManagerRequestController::class, 'index'])->name('manager.requests.index');
    Route::patch('/manager/requests/{serviceRequest}/approve', [ManagerRequestController::class, 'approve'])->name('manager.requests.approve');
    Route::patch('/manager/requests/{serviceRequest}/revision-required', [ManagerRequestController::class, 'markRevisionRequired'])->name('manager.requests.markRevisionRequired');
});

require __DIR__.'/auth.php';
