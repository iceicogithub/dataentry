<?php

use App\Http\Controllers\ActController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Artisan;
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


// Route::get('/rename_column', function () {
//         if (Schema::hasTable('acts') && Schema::hasColumn('acts', 'category')) {
//             DB::statement('ALTER TABLE acts CHANGE category category_id VARCHAR(255)');
//             return 'Column renamed successfully.';
//         } else {
//             return 'Table or old column does not exist.';
//         }
//     });

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('optimize');
    return 'DONE';
});

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');


Route::get('/category', [CategoryController::class, 'index'])->name('category');

// act controller 
Route::get('/act', [ActController::class, 'index'])->name('act');
Route::get('/delete-act/{id}', [ActController::class, 'destroy']);
Route::get('/new_act', [ActController::class, 'new_act'])->name('new_act');
Route::post('/store_new_act', [ActController::class, 'store_new_act']);
Route::post('/update_main_act/{id}', [ActController::class, 'update_main_act']);
Route::get('/edit-main-act/{id}', [ActController::class, 'edit_main_act'])->name('edit-main-act');
Route::get('/view-main-act/{id}', [ActController::class, 'view'])->name('view-main-act');
Route::get('/add-act/{id}', [ActController::class, 'create'])->name('add-act');
Route::get('/edit-act', [ActController::class, 'edit'])->name('edit-act');
Route::post('/store_act/{id}', [ActController::class, 'store']);
Route::get('/get_act_section/{id}', [ActController::class, 'get_act_section'])->name('get_act_section');

// section 
// Route::get('/section', [SectionController::class, 'index'])->name('section');
Route::get('/add-section', [SectionController::class, 'create'])->name('add-section');
Route::get('/sub-section', [SectionController::class, 'SubSection_Index'])->name('sub-section');
Route::get('/add-sub-section', [SectionController::class, 'SubSection_Create'])->name('add-sub-section');
Route::get('/edit-section/{id}', [SectionController::class, 'edit_section'])->name('edit-section');
Route::get('/view-sub-section/{id}', [SectionController::class, 'view_sub_section'])->name('view_sub_section');
Route::get('/delete_sub_section/{id}', [SectionController::class, 'destroy_sub_section']);
Route::post('/update_all_section/{id}', [SectionController::class, 'update']);
Route::get('/delete_section/{id}', [SectionController::class, 'destroy']);
Route::get('/add_below_new_section/{id}/{section_id}/{section_rank}', [SectionController::class, 'add_below_new_section'])->name('add_below_new_section');
Route::post('/add_new_section', [SectionController::class, 'add_new_section']);

// chapter 
Route::get('/chapter', [ChapterController::class, 'index'])->name('chapter');
Route::get('/add-chapter', [ChapterController::class, 'create'])->name('add-chapter');

// regulation 
Route::get('/get_act_regulation/{id}', [RegulationController::class, 'index'])->name('get_act_regulation');
Route::get('/edit-regulation/{id}', [RegulationController::class, 'edit_regulation'])->name('edit-regulation');
Route::post('/update_all_regulation/{id}', [RegulationController::class, 'update']);
Route::get('/delete_regulation/{id}', [RegulationController::class, 'destroy']);
 
Route::get('/export-pdf/{id}', [PdfExportController::class, 'exportToPdf'])->name('export-pdf');

// rules 
Route::get('/edit-rule/{id}', [RulesController::class, 'edit_rule'])->name('edit-rule');
Route::post('/update_all_rule/{id}', [RulesController::class, 'update']);
Route::get('/add_below_new_rule/{id}/{rule_id}/{rule_rank}', [RulesController::class, 'add_below_new_rule'])->name('add_below_new_rule');
Route::post('/add_new_rule', [RulesController::class, 'add_new_rule']);
Route::get('/delete_rule/{id}', [RulesController::class, 'destroy']);
Route::get('/view-sub-rule/{id}', [RulesController::class, 'view_sub_rule'])->name('view_sub_rule');
Route::get('/delete_sub_rule/{id}', [RulesController::class, 'destroy_sub_rule']);

// article 
Route::get('/edit-article/{id}', [ArticleController::class, 'edit_article'])->name('edit-article');
Route::post('/update_all_article/{id}', [ArticleController::class, 'update']);
Route::get('/add_below_new_article/{id}/{article_id}/{article_rank}', [ArticleController::class, 'add_below_new_article'])->name('add_below_new_article');
Route::post('/add_new_article', [ArticleController::class, 'add_new_article']);
Route::get('/delete_article/{id}', [ArticleController::class, 'destroy']);
Route::get('/view-sub-article/{id}', [ArticleController::class, 'view_sub_article'])->name('view_sub_article');
Route::get('/delete_sub_article/{id}', [ArticleController::class, 'destroy_sub_article']);
Route::get('/delete_footnote/{id}', [ArticleController::class, 'delete_footnote']);
