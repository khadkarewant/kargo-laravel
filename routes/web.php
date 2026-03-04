<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/about', [HomeController::class,'about'])->name('about');
use App\Http\Controllers\ServiceRequestController;

Route::get('/requests', [ServiceRequestController::class, 'index'])->name('requests.index');
Route::post('/requests', [ServiceRequestController::class, 'store'])->name('requests.store');

Route::delete('/requests/{request}', [ServiceRequestController::class, 'destroy'])->name('requests.destroy');

Route::get('/requests/{request}/edit', [ServiceRequestController::class, 'edit'])->name('requests.edit');
Route::put('/requests/{request}', [ServiceRequestController::class, 'update'])->name('requests.update');