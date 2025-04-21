<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Collective\CollectiveController;
use App\Http\Controllers\Admin\CriterionController;
use App\Http\Controllers\Admin\CriterionHasNominationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvaluateController;
use App\Http\Controllers\Admin\FirstStepTestController;
use App\Http\Controllers\Admin\JudgeController;
use App\Http\Controllers\Admin\JudgesListController;
use App\Http\Controllers\Admin\Personal\PersonalController;
use App\Http\Controllers\Admin\Personal\PersonalSearchController;
use App\Http\Controllers\Admin\PrecinctController;
use App\Http\Controllers\Admin\PrecinctHasJudgeController;
use App\Http\Controllers\Admin\PrecinctHasNominationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\Test\Collective\CollectiveChangeController;
use App\Http\Controllers\Admin\Test\Collective\FirstStepTestCollectiveController;
use App\Http\Controllers\Admin\Test\Personal\FirstStepTestPersonalController;
use App\Http\Controllers\Admin\Test\Personal\PersonalChangeController;
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

/*Route::get('melumat', function () {
    $personal = \Illuminate\Support\Facades\DB::table('personal_users')->select('id')->get()->count();
    $collective = \Illuminate\Support\Facades\DB::table('collectives')->select('id')->get()->count();
    return "<p style='font-size: 80px'>Fərdi: <span style='color: green'>$personal</span> <br>  Kollektiv: <span style='color: green'>$collective</span></p>";

});*/

Route::get('say', function () {
    $personal = \Illuminate\Support\Facades\DB::table('personal_users_check')->select('id')->count();
    $collective = \Illuminate\Support\Facades\DB::table('collective_users_check')->select('id')->count();
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
        Route::get('precincts-has-judges-search', [PrecinctHasJudgeController::class, 'search'])->name('precincts-has-judges.search');
        Route::get('precincts-has-nominations-search', [PrecinctHasNominationController::class, 'search'])->name('precincts-has-nominations.search');
        Route::get('criterion-has-nomination-search', [CriterionHasNominationController::class, 'search'])->name('criterion-has-nomination.search');
        Route::get('judges-list-search', [JudgesListController::class, 'search'])->name('judges-list.search');
        Route::get('criterion-search', [CriterionController::class, 'search'])->name('criterion.search');

        Route::resources([
            'dashboard' => DashboardController::class,
            'roles' => RoleController::class,
            'users' => UserController::class,
            'precincts' => PrecinctController::class,
            'precincts-has-judges' => PrecinctHasJudgeController::class,
            'judges-list' => JudgesListController::class,
            'precincts-has-nominations' => PrecinctHasNominationController::class,
            'personal-changes' => PersonalChangeController::class,
            'collective-changes' => CollectiveChangeController::class
        ]);

        // Statistics routes
        Route::get('city-statistics', [DashboardController::class, 'cityStatistics'])->name('dashboard.city.statistics');
        Route::get('district-statistics', [DashboardController::class, 'districtStatistics'])->name('dashboard.district.statistics');
        Route::get('karabakh-district-statistics', [DashboardController::class, 'karabakhDistrictStatistics'])->name('dashboard.karabakh.district.statistics');
        Route::get('nomination-statistics', [DashboardController::class, 'nominationStatistics'])->name('dashboard.nomination.statistics');
        Route::get('nomination-district-statistics', [DashboardController::class, 'nominationDistrictStatistics'])->name('nomination.district.statistics');
        Route::get('karabakh-nomination-district-statistics', [DashboardController::class, 'nominationKarabakhDistrictStatistics'])->name('nomination.karabakh.district.statistics');
        Route::get('nomination-city-statistics', [DashboardController::class, 'nominationCityStatistics'])->name('nomination.city.statistics');

        // Users routes
        Route::get('personal-users-list', [PersonalController::class, 'index'])->name('personal.users.list');
        Route::get('personal-users-numbers-list', [PersonalController::class, 'numbersExport'])->name('personal.users.numbers.list');
        Route::get('personal-user-detail/{id}', [PersonalController::class, 'detail'])->name('personal.user.detail');
        Route::get('collective-users-list', [CollectiveController::class, 'index'])->name('collective.users.list');
        Route::get('collective-user-detail/{id}', [CollectiveController::class, 'detail'])->name('collective.user.detail');

        // Excel export
        Route::get('personal-export-excel', [PersonalController::class, 'exportExcel'])->name('personal.export.excel');
        Route::get('collective-export-excel', [CollectiveController::class, 'exportExcel'])->name('collective.export.excel');

        // Qiymetlendirme
        Route::post('evaluate-personal-first-step', [EvaluateController::class, 'evaluatePersonal'])->name('evaluate.personal.first.step');
        Route::post('evaluate-collective-first-step', [EvaluateController::class, 'evaluateCollective'])->name('evaluate.collective.first.step');
        Route::get('judges-evaluate-first-step', [EvaluateController::class, 'judgesEvaluate'])->name('judges.evaluate.first.step');

         //Judge routes
        Route::get('judges', [JudgeController::class, 'judges'])->name('judges');
        Route::get('judges-files', [JudgeController::class, 'judgesFiles'])->name('judges.files');
        Route::get('see-judges-files', [JudgeController::class, 'seeJudgesFiles'])->name('see.judges.files');
        Route::get('see-judges-files-detail/{id}', [JudgeController::class, 'seeJudgesFilesDetail'])->name('see.judges.files.detail');
        Route::post('see-judges-files-detail/{id}', [JudgeController::class, 'seeJudgesFilesDetailStore'])->name('see.judges.files.detail.store');
        Route::get('judges/{id}', [JudgeController::class, 'getJudges']);
        Route::post('judges/search', [JudgeController::class, 'getJudgeUsers'])->name('judges.search');
        Route::post('judges-files/store', [JudgeController::class, 'storeJudgeFiles'])->name('judges.files.store');
        Route::post('judges-files-delete/{id}', [JudgeController::class, 'delete'])->name('judges.files.delete');
        // Test routes
     //  Route::get('testim', [TestController::class, 'menteqTekrarlananMunsif']);
  /*       Route::get('age-category-personal', [AgeCategoryController::class, 'personal']);
        Route::get('age-category-collective', [AgeCategoryController::class, 'collective']);
        Route::get('fin-test', [FinTestController::class, 'index']);
        Route::get('cedvel-test', [CedvelController::class, 'index']);
        Route::get('vereq', [CedvelController::class, 'vereq']);
        Route::get('first-step-user-precincts', [TestController::class, 'fsUserPrecincts']);*/
        // First step test
        // Route::get('first-step-test', [FirstStepTestController::class, 'index']);
      /*  Route::get('contact-city-region-personal', [FirstStepTestPersonalController::class, 'ContactCityRegion']);
        Route::get('contact-city-region-collective', [FirstStepTestCollectiveController::class, 'ContactCityRegion']);
        Route::get('contact-precinct-has-nominations', [FirstStepTestController::class, 'PrecinctHasNominations']);*/
        Route::get('menteqe-user', [FirstStepTestController::class, 'menteqeUser']);
/*        Route::get('menteqe-istirakci-personal', [FirstStepTestPersonalController::class, 'menteqeIstirakci']);
        Route::get('menteqe-istirakci-collective', [FirstStepTestCollectiveController::class, 'menteqeIstirakci']);
        Route::get('menteqe-vaxt-personal', [FirstStepTestPersonalController::class, 'menteqeVaxt']);
        Route::get('menteqe-vaxt-collective', [FirstStepTestCollectiveController::class, 'menteqeVaxt']);*/


    });
});
