<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClanController as AdminClanController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('clans')->name('clans.')->group(function() {
        Route::get('/', [AdminClanController::class, 'index'])->name('index');
        Route::get('/create', [AdminClanController::class, 'create'])->name('create');
        Route::post('/', [AdminClanController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminClanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminClanController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminClanController::class, 'destroy'])->name('destroy');
    });
});