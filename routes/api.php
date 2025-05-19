<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FamilyTreeController;
use App\Http\Controllers\Api\PartuturanController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TreeManipulationController;

Route::prefix('api')->name('api.')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::get('margas', [PersonController::class, 'getMargas']);
    Route::get('partuturan/directory', [PartuturanController::class, 'getTermsDirectory']);

    Route::get('partuturan/relationship', [PartuturanController::class, 'getRelationship']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // User profile
        Route::get('user/profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        // Person data
        Route::get('people/{id}', [PersonController::class, 'show']);
        Route::get('people/search', [PersonController::class, 'search']);
        
        // Family tree data
        // Family Tree API Routes
        Route::get('/family-tree/{familyId}', [FamilyTreeController::class, 'getFamilyTree']);
        Route::get('/family-tree/{familyId}/perspective/{memberId}', [FamilyTreeController::class, 'getFamilyTreeWithSapaan']);
        Route::get('/sapaan/{fromId}/{toId}', [FamilyTreeController::class, 'getSapaan']);
        Route::get('/sapaan/{memberId}/all', [FamilyTreeController::class, 'getAllSapaan']);
        // Route::get('family-tree', [FamilyTreeController::class, 'getTree']);
        // Route::get('family-graph', [FamilyTreeController::class, 'getGraph']);
        // Route::get('family-group', [FamilyTreeController::class, 'getFamilyGroup']);
        
        // Tree manipulation
        Route::post('tree/add-child', [TreeManipulationController::class, 'addChild']);
        Route::post('tree/reorder-children', [TreeManipulationController::class, 'reorderChildren']);
        
        // Partuturan data
        // Route::get('partuturan/relationship', [PartuturanController::class, 'getRelationship']);
        Route::get('partuturan/all-relationships', [PartuturanController::class, 'getAllRelationships']);
    });
});