<?php

use App\Http\Controllers\Api\AllCityController;
use App\Http\Controllers\Api\EducationSchoolController;
use App\Http\Controllers\Api\MNRegionController;
use App\Http\Controllers\Api\NominationController;
use App\Http\Controllers\Api\PersonalData\CollectiveDataController;
use App\Http\Controllers\Api\PersonalData\PersonalDataController;
use App\Http\Controllers\Api\Registration\CollectiveRegistrationController;
use App\Http\Controllers\Api\Registration\PersonalRegistrationController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Test\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$date = date("Y-m-d H:i:s");
if ($date <= '2025-04-01 00:00:00') {

    Route::middleware('throttle:40,1')->group(function () {
    //Personal data routes
    Route::get('get-personal-data', [PersonalDataController::class, 'getPersonalData']);
    Route::get('personal-parent-data', [PersonalDataController::class, 'personalParentData']);
    Route::get('get-collective-data', [CollectiveDataController::class, 'getCollectiveData']);
    Route::get('collective-director-data', [CollectiveDataController::class, 'collectiveDirectorData']);
    // Personal user routes
    Route::post('personal-registration-first-step', [PersonalRegistrationController::class, 'firstStep']);
    Route::post('personal-registration-second-step', [PersonalRegistrationController::class, 'secondStep']);
    Route::post('personal-registration-third-step', [PersonalRegistrationController::class, 'thirdStep']);
    Route::post('personal-registration', [PersonalRegistrationController::class, 'store']);
    // Collective user routes
    Route::post('collective-registration-first-step', [CollectiveRegistrationController::class, 'firstStep']);
    Route::post('collective-registration-second-step', [CollectiveRegistrationController::class, 'secondStep']);
    Route::post('collective-registration-check', [CollectiveRegistrationController::class, 'check']);
    Route::post('collective-registration-third-step', [CollectiveRegistrationController::class, 'thirdStep']);
    Route::post('collective-registration', [CollectiveRegistrationController::class, 'store']);
});

}
    // Select lists
    Route::get('education-schools-types', [EducationSchoolController::class, 'index']);
    Route::get('education-schools', [EducationSchoolController::class, 'getSchools']);
    Route::get('all-cities', [AllCityController::class, 'index']);
    Route::get('mn-regions', [MNRegionController::class, 'index']);
    Route::get('personal-nominations', [NominationController::class, 'personalNominations']);
    Route::get('collective-nominations', [NominationController::class, 'collectiveNominations']);


    // Pdf dowload route
    Route::get('/download-pdf', function () {
        return response()->file(storage_path('app/public/downloads/1.pdf'));
    });
    Route::middleware('throttle:7,1')->group(function () {
        // Searches
        Route::get('check-personal', [SearchController::class, 'searchPersonalData']);
        Route::get('check-collective', [SearchController::class, 'searchCollectiveData']);
        Route::get('time-place-personal', [SearchController::class, 'timePlacePersonal']);
        Route::get('time-place-collective', [SearchController::class, 'timePlaceCollective']);
    });


 /*    Route::get('final-results', [FinalResultsController::class, 'index']);
   Route::get('second-step-time-place', [SecondStepSearchController::class, 'timePlace']);
*/


