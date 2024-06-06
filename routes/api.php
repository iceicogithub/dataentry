<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MainActController;
use App\Http\Controllers\API\RuleApiController;
use App\Http\Controllers\API\RegulationApiController;
use App\Http\Controllers\API\ManualApiController;
use App\Http\Controllers\API\SchemeGuidelineApiController;
use App\Http\Controllers\API\OrderApiController;
use App\Http\Controllers\API\NotificationApiController;
use App\Http\Controllers\API\CircularApiController;
use App\Http\Controllers\API\PolicyApiController;
use App\Http\Controllers\API\FormApiController;
use App\Http\Controllers\API\ReleaseApiController;
use App\Http\Controllers\API\OrdinanceApiController;

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
Route::get('/types', [MainActController::class, 'types']);

Route::get('/rule/{act_id}', [RuleApiController::class, 'index']);
Route::get('/rule_content/{rule_id}', [RuleApiController::class, 'create']);
Route::get('/rule_pdf/{rule_id}', [RuleApiController::class, 'pdf']);

Route::get('/regulation/{act_id}', [RegulationApiController::class, 'index']);
Route::get('/regulation_content/{regulation_id}', [RegulationApiController::class, 'create']);
Route::get('/regulation_pdf/{regulation_id}', [RegulationApiController::class, 'pdf']);

Route::get('/manuals/{act_id}', [ManualApiController::class, 'index']);
Route::get('/manuals_content/{manual_id}', [ManualApiController::class, 'create']);

Route::get('/scheme_guideline/{act_id}', [SchemeGuidelineApiController::class, 'index']);
Route::get('/scheme_guideline_content/{schemeGuidlId}', [SchemeGuidelineApiController::class, 'create']);
Route::get('/scheme_guideline_pdf/{schemeGuidlId}', [SchemeGuidelineApiController::class, 'pdf']);

Route::get('/order/{act_id}', [OrderApiController::class, 'index']);
Route::get('/order_content/{order_id}', [OrderApiController::class, 'create']);
Route::get('/order_pdf/{order_id}', [OrderApiController::class, 'pdf']);

Route::get('/notifications/{act_id}', [NotificationApiController::class, 'index']);
Route::get('/notifications_content/{notification_id}', [NotificationApiController::class, 'create']);

Route::get('/circulars/{act_id}', [CircularApiController::class, 'index']);
Route::get('/circulars_content/{circular_id}', [CircularApiController::class, 'create']);

Route::get('/policy/{act_id}', [PolicyApiController::class, 'index']);
Route::get('/policy_content/{policy_id}', [PolicyApiController::class, 'create']);

Route::get('/form/{act_id}', [FormApiController::class, 'index']);
Route::get('/form_content/{form_id}', [FormApiController::class, 'create']);

Route::get('/release/{act_id}', [ReleaseApiController::class, 'index']);
Route::get('/release_content/{release_id}', [ReleaseApiController::class, 'create']);

Route::get('/ordinance/{act_id}', [OrdinanceApiController::class, 'index']);
Route::get('/ordinance_content/{ordinance_id}', [OrdinanceApiController::class, 'create']);
Route::get('/ordinance_pdf/{ordinance_id}', [OrdinanceApiController::class, 'pdf']);


