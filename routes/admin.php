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

use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Admin\ChildController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('people', PersonController::class);

    Route::resource('users', UserController::class)
        ->except(['show']);

    // Marriages
    Route::resource('marriages', MarriageController::class)
        ->except(['show']);
    
    // Children
    Route::resource('children', ChildController::class)
        ->except(['show']);
});