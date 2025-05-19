<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
// use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\MargaController;
// use App\Http\Controllers\Admin\PersonController;
// use App\Http\Controllers\Admin\ParentChildController;
// use App\Http\Controllers\Admin\MarriageController;
// use App\Http\Controllers\Admin\PartuturanRuleController;
// use App\Http\Controllers\Admin\PartuturanCategoryController;
// use App\Http\Controllers\Admin\PartuturanTermController;
// use App\Http\Controllers\Admin\RelationshipPatternController;

use App\Http\Controllers\Admin\MargaController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\FamilyMemberController;
use App\Http\Controllers\Admin\SpouseController;
use App\Http\Controllers\Admin\SapaanTermController;
use App\Http\Controllers\Admin\SapaanRuleController;
use App\Http\Controllers\Admin\UserController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Route::resource('users', UserController::class);
    // Route::resource('margas', MargaController::class);
    // Route::resource('people', PersonController::class);

    // // Parent-Child relationship routes
    // Route::get('parent-child/create', [ParentChildController::class, 'create'])->name('parent-child.create');
    // Route::post('parent-child', [ParentChildController::class, 'store'])->name('parent-child.store');
    // Route::delete('parent-child', [ParentChildController::class, 'destroy'])->name('parent-child.destroy');
    // Route::get('parent-child/{parentChild}/edit', [ParentChildController::class, 'edit'])->name('parent-child.edit');
    // Route::put('parent-child/{parentChild}', [ParentChildController::class, 'update'])->name('parent-child.update');
    
    // // AJAX endpoints for relationship management
    // Route::post('parent-child/reorder', [ParentChildController::class, 'reorderChildren'])->name('parent-child.reorder');

    // Route::resource('marriages', MarriageController::class);
    // Route::resource('partuturan-rules', PartuturanRuleController::class);
    // Route::resource('partuturan-categories', PartuturanCategoryController::class);
    // Route::resource('partuturan-terms', PartuturanTermController::class);
    // Route::resource('relationship-patterns', RelationshipPatternController::class);

    Route::resource("margas", MargaController::class);
    Route::resource("families", FamilyController::class);
    
    Route::resource("families.family_members", FamilyMemberController::class)->shallow();
    // Shallow nesting means routes like /family_members/{family_member} don"t need /families/{family} prefix
    // For spouses, which are a relationship between two family_members within a family:
    // GET /families/{family}/family_members/{family_member}/spouses -> SpouseController@index
    // GET /families/{family}/family_members/{family_member}/spouses/create -> SpouseController@create
    // POST /families/{family}/family_members/{family_member}/spouses -> SpouseController@store
    // GET /families/{family}/family_members/{family_member}/spouses/{spouse} -> SpouseController@show (might not be needed if pivot data is main focus)
    // GET /families/{family}/family_members/{family_member}/spouses/{spouse}/edit -> SpouseController@edit
    // PUT/PATCH /families/{family}/family_members/{family_member}/spouses/{spouse} -> SpouseController@update
    // DELETE /families/{family}/family_members/{family_member}/spouses/{spouse} -> SpouseController@destroy
    Route::resource("families.family_members.spouses", SpouseController::class)->parameters([
        "spouses" => "spouse" // Parameter name for the spouse FamilyMember model
    ]);

    Route::resource("sapaan_terms", SapaanTermController::class);
    Route::resource("sapaan_rules", SapaanRuleController::class);
    Route::resource("users", UserController::class);
});