<?php

use App\Http\Controllers\ActController;
use App\Http\Controllers\AnnexureController;
use App\Http\Controllers\AppendicesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\stscheduleController;
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
Route::post('/update_all_section/{id}', [SectionController::class, 'update']);
Route::get('/delete_sub_section/{id}', [SectionController::class, 'destroy_sub_section']);
Route::get('/delete_section/{id}', [SectionController::class, 'destroy']);
Route::get('/add_below_new_section/{id}/{section_id}', [SectionController::class, 'add_below_new_section'])->name('add_below_new_section');
Route::post('/add_new_section', [SectionController::class, 'add_new_section']);

// chapter 
Route::get('/chapter', [ChapterController::class, 'index'])->name('chapter');
Route::get('/add-chapter', [ChapterController::class, 'create'])->name('add-chapter');


Route::get('/export-pdf/{id}', [PdfExportController::class, 'exportToPdf'])->name('export-pdf');

// rules 
Route::get('/edit-rule/{id}', [RulesController::class, 'edit_rule'])->name('edit-rule');
Route::post('/update_all_rule/{id}', [RulesController::class, 'update']);
Route::get('/add_below_new_rule/{id}/{rule_id}', [RulesController::class, 'add_below_new_rule'])->name('add_below_new_rule');
Route::post('/add_new_rule', [RulesController::class, 'add_new_rule']);
Route::get('/delete_rule/{id}', [RulesController::class, 'destroy']);
Route::get('/view-sub-rule/{id}', [RulesController::class, 'view_sub_rule'])->name('view_sub_rule');
Route::get('/delete_sub_rule/{id}', [RulesController::class, 'destroy_sub_rule']);

// article 
Route::get('/edit-article/{id}', [ArticleController::class, 'edit_article'])->name('edit-article');
Route::post('/update_all_article/{id}', [ArticleController::class, 'update']);
Route::get('/add_below_new_article/{id}/{article_id}}', [ArticleController::class, 'add_below_new_article'])->name('add_below_new_article');
Route::post('/add_new_article', [ArticleController::class, 'add_new_article']);
Route::get('/delete_article/{id}', [ArticleController::class, 'destroy']);
Route::get('/view-sub-article/{id}', [ArticleController::class, 'view_sub_article'])->name('view_sub_article');
Route::get('/delete_sub_article/{id}', [ArticleController::class, 'destroy_sub_article']);
Route::get('/delete_footnote/{id}', [ArticleController::class, 'delete_footnote']);

// regulation 
Route::get('/edit-regulation/{id}', [RegulationController::class, 'edit_regulation'])->name('edit_regulation');
Route::post('/update_all_regulation/{id}', [RegulationController::class, 'update']);
Route::get('/add_below_new_regulation/{id}/{regulation_id}}', [RegulationController::class, 'add_below_new_regulation'])->name('add_below_new_regulation');
Route::post('/add_new_regulation', [RegulationController::class, 'add_new_regulation']);
Route::get('/delete_regulation/{id}', [RegulationController::class, 'destroy']);
Route::get('/view-sub-regulation/{id}', [RegulationController::class, 'view_sub_regulation'])->name('view_sub_regulation');
Route::get('/delete_sub_regulation/{id}', [RegulationController::class, 'destroy_sub_regulation']);
Route::get('/delete_footnote/{id}', [RegulationController::class, 'delete_footnote']);

// list 
Route::get('/edit-list/{id}', [ListController::class, 'edit_list'])->name('edit_list');
Route::post('/update_all_list/{id}', [ListController::class, 'update']);
Route::get('/add_below_new_list/{id}/{list_id}', [ListController::class, 'add_below_new_list'])->name('add_below_new_list');
Route::post('/add_new_list', [ListController::class, 'add_new_list']);
Route::get('/delete_list/{id}', [ListController::class, 'destroy']);
Route::get('/view-sub-list/{id}', [ListController::class, 'view_sub_list'])->name('view_sub_list');
Route::get('/delete_sub_list/{id}', [ListController::class, 'destroy_sub_list']);
Route::get('/delete_footnote/{id}', [ListController::class, 'delete_footnote']);


// part 
Route::get('/edit-part/{id}', [PartController::class, 'edit_part'])->name('edit_part');
Route::post('/update_all_part/{id}', [PartController::class, 'update']);
Route::get('/add_below_new_part/{id}/{part_id}', [PartController::class, 'add_below_new_part'])->name('add_below_new_part');
Route::post('/add_new_part', [PartController::class, 'add_new_part']);
Route::get('/delete_part/{id}', [PartController::class, 'destroy']);
Route::get('/view-sub-part/{id}', [PartController::class, 'view_sub_part'])->name('view_sub_part');
Route::get('/delete_sub_part/{id}', [PartController::class, 'destroy_sub_part']);
Route::get('/delete_footnote/{id}', [PartController::class, 'delete_footnote']);

// Appendices
Route::get('/edit-appendices/{id}', [AppendicesController::class, 'edit_appendices'])->name('edit_appendices');
Route::post('/update_all_appendices/{id}', [AppendicesController::class, 'update']);
Route::get('/add_below_new_appendices/{id}/{appendices_id}', [AppendicesController::class, 'add_below_new_appendices'])->name('add_below_new_appendices');
Route::post('/add_new_appendices', [AppendicesController::class, 'add_new_appendices']);
Route::get('/delete_appendices/{id}', [AppendicesController::class, 'destroy']);
Route::get('/view-sub-appendices/{id}', [AppendicesController::class, 'view_sub_appendices'])->name('view_sub_appendices');
Route::get('/delete_sub_appendices/{id}', [AppendicesController::class, 'destroy_sub_appendices']);
Route::get('/delete_footnote/{id}', [AppendicesController::class, 'delete_footnote']);

// Order
Route::get('/edit-order/{id}', [OrderController::class, 'edit_order'])->name('edit_order');
Route::post('/update_all_order/{id}', [OrderController::class, 'update']);
Route::get('/add_below_new_order/{id}/{order_id}', [OrderController::class, 'add_below_new_order'])->name('add_below_new_order');
Route::post('/add_new_order', [OrderController::class, 'add_new_order']);
Route::get('/delete_order/{id}', [OrderController::class, 'destroy']);
Route::get('/view-sub-order/{id}', [OrderController::class, 'view_sub_order'])->name('view_sub_order');
Route::get('/delete_sub_order/{id}', [OrderController::class, 'destroy_sub_order']);
Route::get('/delete_footnote/{id}', [OrderController::class, 'delete_footnote']);

// Stschedule
Route::get('/edit-stschedule/{id}', [stscheduleController::class, 'edit_stschedule'])->name('edit_stschedule');
Route::post('/update_all_stschedule/{id}', [stscheduleController::class, 'update']);
Route::get('/add_below_new_stschedule/{id}/{stschedule_id}', [stscheduleController::class, 'add_below_new_stschedule'])->name('add_below_new_stschedule');
Route::post('/add_new_stschedule', [stscheduleController::class, 'add_new_stschedule']);
Route::get('/delete_stschedule/{id}', [stscheduleController::class, 'destroy']);
Route::get('/view-sub-stschedule/{id}', [stscheduleController::class, 'view_sub_stschedule'])->name('view_sub_stschedule');
Route::get('/delete_sub_stschedule/{id}', [stscheduleController::class, 'destroy_sub_stschedule']);
Route::get('/delete_footnote/{id}', [stscheduleController::class, 'delete_footnote']);

// Annexure
Route::get('/edit-annexure/{id}', [AnnexureController::class, 'edit_annexure'])->name('edit_annexure');
Route::post('/update_all_annexure/{id}', [AnnexureController::class, 'update']);
Route::get('/add_below_new_annexure/{id}/{annexure_id}', [AnnexureController::class, 'add_below_new_annexure'])->name('add_below_new_annexure');
Route::post('/add_new_annexure', [AnnexureController::class, 'add_new_annexure']);
Route::get('/delete_annexure/{id}', [AnnexureController::class, 'destroy']);
Route::get('/view-sub-annexure/{id}', [AnnexureController::class, 'view_sub_annexure'])->name('view_sub_annexure');
Route::get('/delete_sub_annexure/{id}', [AnnexureController::class, 'destroy_sub_annexure']);
Route::get('/delete_footnote/{id}', [AnnexureController::class, 'delete_footnote']);
