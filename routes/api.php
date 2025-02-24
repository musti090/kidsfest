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
use App\Http\Controllers\Api\FinalResultsController;
use App\Http\Controllers\Api\SecondStepSearchController;
use App\Http\Controllers\Api\SpecialArtSchoolController;
use App\Http\Controllers\Api\Test\TestController;
use Illuminate\Http\Request;
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

   // Route::middleware('ip.whitelist')->group(function () {
    //Personal data routes
    Route::get('get-personal-data', [PersonalDataController::class, 'getPersonalData']);
    Route::get('personal-parent-data', [PersonalDataController::class, 'personalParentData']);
    Route::get('get-collective-data', [CollectiveDataController::class, 'getCollectiveData']);
    Route::get('collective-director-data', [CollectiveDataController::class, 'collectiveDirectorData']);
    // Personal user routes
   // Route::get('test', [TestController::class, 'test']);
    Route::post('personal-registration-first-step', [PersonalRegistrationController::class, 'firstStep']);
    Route::post('personal-registration-second-step', [PersonalRegistrationController::class, 'secondStep']);
    Route::post('personal-registration-third-step', [PersonalRegistrationController::class, 'thirdStep']);
    Route::/*middleware('throttle:1,1')->*/post('personal-registration', [PersonalRegistrationController::class, 'store']);
    // Collective user routes
    Route::post('collective-registration-first-step', [CollectiveRegistrationController::class, 'firstStep']);
    Route::post('collective-registration-second-step', [CollectiveRegistrationController::class, 'secondStep']);
    Route::post('collective-registration-check', [CollectiveRegistrationController::class, 'check']);
    Route::post('collective-registration-third-step', [CollectiveRegistrationController::class, 'thirdStep']);
    Route::post('collective-registration', [CollectiveRegistrationController::class, 'store']);
    // Select lists
    Route::get('education-schools-types', [EducationSchoolController::class, 'index']);
    Route::get('education-schools', [EducationSchoolController::class, 'getSchools']);
    Route::get('all-cities', [AllCityController::class, 'index']);
    Route::get('mn-regions', [MNRegionController::class, 'index']);
    Route::get('personal-nominations', [NominationController::class, 'personalNominations']);
    Route::get('collective-nominations', [NominationController::class, 'collectiveNominations']);
 //   });
    }
    // Pdf dowload route
    Route::get('/download-pdf', function () {
        return response()->file(storage_path('app/public/downloads/məlumat.pdf'));
    });

    // Searches
    Route::get('search', [SearchController::class, 'index']);
 /*   Route::get('time-place', [SearchController::class, 'timePlace']);
    Route::get('final-results', [FinalResultsController::class, 'index']);
    Route::get('second-step-time-place', [SecondStepSearchController::class, 'timePlace']);
*/


