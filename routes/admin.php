<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MargaController;
use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Admin\ParentChildController;
use App\Http\Controllers\Admin\MarriageController;
use App\Http\Controllers\Admin\PartuturanRuleController;
use App\Http\Controllers\Admin\PartuturanCategoryController;
use App\Http\Controllers\Admin\PartuturanTermController;
use App\Http\Controllers\Admin\RelationshipPatternController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('margas', MargaController::class);
    Route::resource('people', PersonController::class);

    // Parent-Child relationship routes
    Route::get('parent-child/create', [ParentChildController::class, 'create'])->name('parent-child.create');
    Route::post('parent-child', [ParentChildController::class, 'store'])->name('parent-child.store');
    Route::delete('parent-child', [ParentChildController::class, 'destroy'])->name('parent-child.destroy');
    Route::get('parent-child/{parentChild}/edit', [ParentChildController::class, 'edit'])->name('parent-child.edit');
    Route::put('parent-child/{parentChild}', [ParentChildController::class, 'update'])->name('parent-child.update');
    
    // AJAX endpoints for relationship management
    Route::post('parent-child/reorder', [ParentChildController::class, 'reorderChildren'])->name('parent-child.reorder');

    Route::resource('marriages', MarriageController::class);
    Route::resource('partuturan-rules', PartuturanRuleController::class);
    Route::resource('partuturan-categories', PartuturanCategoryController::class);
    Route::resource('partuturan-terms', PartuturanTermController::class);
    Route::resource('relationship-patterns', RelationshipPatternController::class);
});