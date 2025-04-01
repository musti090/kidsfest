<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Collective\CollectiveController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Personal\PersonalController;
use App\Http\Controllers\Admin\Personal\PersonalSearchController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Test\AgeCategoryController;
use App\Http\Controllers\Test\CedvelController;
use App\Http\Controllers\Test\FinTestController;
use App\Http\Controllers\Test\TestController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('melumat', function () {
    $personal = \Illuminate\Support\Facades\DB::table('personal_users')->select('id')->get()->count();
    $collective = \Illuminate\Support\Facades\DB::table('collectives')->select('id')->get()->count();
    return "<p style='font-size: 80px'>Fərdi: <span style='color: green'>$personal</span> <br>  Kollektiv: <span style='color: green'>$collective</span></p>";

});



/*Route::get('sifre', function () {
    return Hash::make('MuS@R$F92Ly#n64Ht');
    return Hash::make('NfD@z8$w2Ly#n6Jt4');
});*/

//Route::get('add',[\App\Http\Controllers\Api\Test\TestController::class,'add']);
Route::name('backend.')->prefix('my-admin')->group(function () {
    Route::middleware('throttle:5,1')->group(function () {
        // Login routes
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login/submit', [LoginController::class, 'login'])->name('login.submit');
    });
    Route::middleware(['auth', 'permission:view dashboard'])->group(function () {

        // Logout route
        Route::post('logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

        Route::resources([
            'dashboard' => DashboardController::class,
            'roles' => RoleController::class,
            'users' => UserController::class,
        ]);

        // Statistics routes
        Route::get('city-statistics',[DashboardController::class,'cityStatistics'])->name('dashboard.city.statistics');
        Route::get('district-statistics',[DashboardController::class,'districtStatistics'])->name('dashboard.district.statistics');
        Route::get('nomination-statistics',[DashboardController::class,'nominationStatistics'])->name('dashboard.nomination.statistics');
        Route::get('nomination-district-statistics', [DashboardController::class, 'nominationDistrictStatistics'])->name('nomination.district.statistics');

        // Users routes
        Route::get('personal-users-list',[PersonalController::class,'index'])->name('personal.users.list');
        Route::get('personal-user-detail/{id}',[PersonalController::class,'detail'])->name('personal.user.detail');
        Route::get('collective-users-list',[CollectiveController::class,'index'])->name('collective.users.list');
        Route::get('collective-user-detail/{id}',[CollectiveController::class,'detail'])->name('collective.user.detail');

        // Excel export
        Route::get('personal-export-excel',[PersonalController::class,'exportExcel'])->name('personal.export.excel');
        Route::get('collective-export-excel',[CollectiveController::class,'exportExcel'])->name('collective.export.excel');

        // Test routes
        Route::get('testim', [TestController::class, 'test']);
        Route::get('age-category-personal', [AgeCategoryController::class, 'personal']);
        Route::get('age-category-collective', [AgeCategoryController::class, 'collective']);
        Route::get('fin-test', [FinTestController::class, 'index']);
        Route::get('cedvel-test', [CedvelController::class, 'index']);

    });
});
