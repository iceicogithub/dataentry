<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MainActController;
use App\Http\Controllers\API\RuleApiController;
use App\Http\Controllers\API\RegulationApiController;
use App\Http\Controllers\API\ManualApiController;
use App\Http\Controllers\API\SchemeGuidelineApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/act', [MainActController::class, 'show']);
Route::get('/mainact/{id}', [MainActController::class, 'index']);
Route::get('/generate_pdf/{id}', [MainActController::class, 'create']);

Route::get('/rule/{act_id}', [RuleApiController::class, 'index']);
Route::get('/rule_content/{rule_id}', [RuleApiController::class, 'create']);

Route::get('/regulation/{act_id}', [RegulationApiController::class, 'index']);
Route::get('/regulation_content/{regulation_id}', [RegulationApiController::class, 'create']);

Route::get('/manuals/{act_id}', [ManualApiController::class, 'index']);
Route::get('/manuals_content/{manual_id}', [ManualApiController::class, 'create']);

Route::get('/scheme_guideline/{act_id}', [SchemeGuidelineApiController::class, 'index']);
Route::get('/scheme_guideline_content/{schemeGuidlId}', [SchemeGuidelineApiController::class, 'create']);


