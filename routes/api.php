<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FamilyTreeController;

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/family-tree/{individualId}', [FamilyTreeController::class, 'getTree'])->name('getTree');
});