<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FamilyMemberController;
use App\Http\Controllers\Admin\ClanController as AdminClanController;
use App\Http\Controllers\Admin\IndividualController as AdminIndividualController;
use App\Http\Controllers\Admin\RelationshipController as AdminRelationshipController;
use App\Http\Controllers\Admin\MarriageController;
use App\Http\Controllers\Admin\RelationController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('family_members', FamilyMemberController::class);
    Route::resource('marriages', MarriageController::class);
    Route::resource('relations', RelationController::class);

    Route::prefix('clans')->name('clans.')->group(function() {
        Route::get('/', [AdminClanController::class, 'index'])->name('index');
        Route::get('/create', [AdminClanController::class, 'create'])->name('create');
        Route::post('/', [AdminClanController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminClanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminClanController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminClanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('individual')->name('individual.')->group(function() {
        Route::get('/', [AdminIndividualController::class, 'index'])->name('index');
        Route::get('/create', [AdminIndividualController::class, 'create'])->name('create');
        Route::post('/', [AdminIndividualController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminIndividualController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminIndividualController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminIndividualController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('relationships')->name('relationships.')->group(function() {
        Route::get('/', [AdminRelationshipController::class, 'index'])->name('index');
        Route::get('/create', [AdminRelationshipController::class, 'create'])->name('create');
        Route::post('/', [AdminRelationshipController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminRelationshipController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminRelationshipController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminRelationshipController::class, 'destroy'])->name('destroy');
    });
});