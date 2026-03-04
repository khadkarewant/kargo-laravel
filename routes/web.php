<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/about', [HomeController::class,'about'])->name('about');
use App\Http\Controllers\ServiceRequestController;

Route::get('/requests', [ServiceRequestController::class, 'index'])->name('requests.index');
Route::post('/requests', [ServiceRequestController::class, 'store'])->name('requests.store');