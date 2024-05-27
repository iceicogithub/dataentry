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
use App\Http\Controllers\MainRuleController;
use App\Http\Controllers\MainRegulationController;
use App\Http\Controllers\MainSchemeGuidelinesController;
use App\Http\Controllers\ActAmendmentController;
use App\Http\Controllers\MainOrderController;
use App\Http\Controllers\ManualsController;
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
Route::get('/delete_parts/{id}', [ActController::class, 'delete_parts'])->name('delete_parts');
Route::get('/delete_chapter/{id}', [ActController::class, 'delete_chapter'])->name('delete_chapter');
Route::get('/delete_prilimiary/{id}', [ActController::class, 'delete_priliminary'])->name('delete_priliminary');
Route::get('/delete_schedule/{id}', [ActController::class, 'delete_schedule'])->name('delete_schedule');
Route::get('/delete_appendix/{id}', [ActController::class, 'delete_appendix'])->name('delete_appendix');
Route::get('/delete_main_order/{id}', [ActController::class, 'delete_main_order'])->name('delete_main_order');
// Route::post('/add_below_new_maintype/{id}', [ActController::class, 'store_new_main_type'])->name('add_new_main_type');
Route::get('/add_below_new_maintype/{act_id}/{main_id}/{id}', [ActController::class, 'add_new_main_type'])->name('add_new_main_type');
Route::post('/add_new_main_type/{id}', [ActController::class, 'store_new_main_type'])->name('store_new_main_type');
Route::get('/edit_legislation_name/{id}', [ActController::class, 'edit_legislation_name'])->name('edit_legislation_name');
Route::post('/update_legislation/{id}', [ActController::class, 'update_legislation'])->name('update_legislation');


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
Route::post('/add_new_section', [SectionController::class, 'add_new_section'])->name('add_new_section');


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



//Main Rule
Route::get('/get_rule/{id}',[MainRuleController::class,'index'])->name('get_rule');
Route::get('/add-rule/{id}',[MainRuleController::class,'create']);
Route::post('/store_rule/{id}',[MainRuleController::class,'store']);
Route::get('/edit-rule-sub/{id}',[MainRuleController::class,'edit']);
Route::get('/new_rule/{id}',[MainRuleController::class,'add_new_rule'])->name('new_rule');
Route::post('/store_new_rule',[MainRuleController::class,'store_new_rule'])->name('store_new_rule');
Route::get('/edit_new_rule/{id}',[MainRuleController::class,'edit_new_rule'])->name('edit_new_rule');
Route::post('/update_new_rule/{id}',[MainRuleController::class,'update_new_rule'])->name('update_new_rule');
Route::get('/add_below_new_rule_maintype/{newRuleId}/{id}',[MainRuleController::class,'add_below_new_rule_maintype'])->name('add_below_new_rule_maintype');
Route::post('/store_rule_maintype',[MainRuleController::class,'store_rule_maintype'])->name('store_rule_maintype');
Route::get('/delete_rule_maintype/{id}',[MainRuleController::class,'delete_rule_maintype'])->name('delete_rule_maintype');
Route::get('/edit_ruleTable/{id}',[MainRuleController::class,'edit_ruleTable'])->name('edit_ruleTable');
Route::post('/update_main_rule/{id}',[MainRuleController::class,'update_main_rule'])->name('update_main_rule');
Route::get('/view_rule_sub/{id}',[MainRuleController::class,'view_rule_sub'])->name('view_rule_sub');
Route::get('/delete_rule_sub/{id}',[MainRuleController::class,'delete_rule_sub'])->name('delete_rule_sub');
Route::get('/delete_rulestbl/{id}',[MainRuleController::class,'delete_rulestbl'])->name('delete_rulestbl');
Route::get('/add_below_new_ruletbl/{ruleMainId}/{id}',[MainRuleController::class,'add_below_new_ruletbl'])->name('add_below_new_ruletbl');
Route::post('/add_new_ruletbl',[MainRuleController::class,'add_new_ruletbl'])->name('add_new_ruletbl');
Route::get('/delete_new_rule/{id}',[MainRuleController::class,'delete_new_rule'])->name('delete_new_rule');
Route::get('/delete_rule_footnote/{id}',[MainRuleController::class,'delete_rule_footnote'])->name('delete_rule_footnote');
Route::get('/view_new_rule/{id}',[MainRuleController::class,'view_new_rule'])->name('view_new_rule');
Route::get('/export_rule_pdf/{id}',[MainRuleController::class,'export_rule_pdf'])->name('export_rule_pdf');


//Regulation
Route::get('/get_regulation/{id}',[MainRegulationController::class,'index'])->name('get_regulation');
Route::get('/new_regulation/{id}',[MainRegulationController::class,'add_new_regulation'])->name('new_regulation');
Route::post('/store_new_regulation',[MainRegulationController::class,'store_new_regulation'])->name('store_new_regulation');
Route::get('/edit_new_regulation/{id}',[MainRegulationController::class,'edit_new_regulation'])->name('edit_new_regulation');
Route::post('/update_new_regulation/{id}',[MainRegulationController::class,'update_new_regulation'])->name('update_new_regulation');
Route::get('/add_regulation/{id}',[MainRegulationController::class,'create']);
Route::post('/store_regulation/{id}',[MainRegulationController::class,'store']);
Route::get('/add_below_new_regulation_maintype/{newRgltnId}/{id}',[MainRegulationController::class,'add_below_new_regulation_maintype'])->name('add_below_new_regulation_maintype');
Route::post('/store_regulation_maintype',[MainRegulationController::class,'store_regulation_maintype'])->name('store_regulation_maintype');
Route::get('/delete_regulation_maintype/{id}',[MainRegulationController::class,'delete_regulation_maintype'])->name('delete_regulation_maintype');
Route::get('/edit_regulationTable/{id}',[MainRegulationController::class,'edit_regulationTable'])->name('edit_regulationTable');
Route::post('/update_main_regulation/{id}',[MainRegulationController::class,'update_main_regulation'])->name('update_main_regulation');
Route::get('/view_regulation_sub/{id}',[MainRegulationController::class,'view_regulation_sub'])->name('view_regulation_sub');
Route::get('/delete_regulation_sub/{id}',[MainRegulationController::class,'delete_regulation_sub'])->name('delete_regulation_sub');
Route::get('/delete_regulationstbl/{id}',[MainRegulationController::class,'delete_regulationstbl'])->name('delete_regulationstbl');
Route::get('/add_below_new_rgtlntbl/{rgltntbl}/{id}',[MainRegulationController::class,'add_below_new_rgtlntbl'])->name('add_below_new_rgtlntbl');
Route::post('/add_new_regulationtbl',[MainRegulationController::class,'add_new_regulationtbl'])->name('add_new_regulationtbl');
Route::get('/delete_new_regulation/{id}',[MainRegulationController::class,'delete_new_regulation'])->name('delete_new_regulation');
Route::get('/delete_regulation_footnote/{id}',[MainRegulationController::class,'delete_regulation_footnote'])->name('delete_regulation_footnote');
Route::get('/view_new_regulation/{id}',[MainRegulationController::class,'view_new_regulation'])->name('view_new_regulation');
Route::get('/export_regulation_pdf/{id}',[MainRegulationController::class,'export_regulation_pdf'])->name('export_regulation_pdf');


Route::get('/get_amendment_act/{id}',[ActAmendmentController::class, 'index'])->name('get_amendment_act');
Route::get('/new_act_amendment/{id}',[ActAmendmentController::class, 'create'])->name('new_act_amendment');
Route::post('/store_act_amendment',[ActAmendmentController::class, 'store'])->name('store_act_amendment');
Route::post('/update_act_amendment/{id}',[ActAmendmentController::class, 'update'])->name('update_act_amendment');
Route::get('/view_act_amendment/{id}',[ActAmendmentController::class, 'show'])->name('show_act_amendment');
Route::get('/delete_act_amendment/{id}',[ActAmendmentController::class, 'destroy'])->name('delete_act_amendment');


Route::get('/get_schemes_guidelines/{id}',[MainSchemeGuidelinesController::class,'index'])->name('get_schemes_guidelines');
Route::get('/new_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'new_scheme_guidelines'])->name('new_scheme_guidelines');
Route::post('/store_new_scheme_guidelines',[MainSchemeGuidelinesController::class,'store_new_scheme_guidelines'])->name('store_new_scheme_guidelines');
Route::get('/edit_new_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'edit_new_scheme_guidelines'])->name('edit_new_scheme_guidelines');
Route::post('/update_new_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'update_new_scheme_guidelines'])->name('update_new_scheme_guidelines');
Route::get('/add_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'create'])->name('add_scheme_guidelines');
Route::post('/store_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'store'])->name('store_scheme_guidelines');
Route::get('/add_below_new_scheme_guidelines_maintype/{newschmid}/{id}',[MainSchemeGuidelinesController::class,'add_below_new_scheme_guidelines_maintype'])->name('add_below_new_scheme_guidelines_maintype');
Route::post('/store_scheme_guidelines_maintype',[MainSchemeGuidelinesController::class,'store_scheme_guidelines_maintype'])->name('store_scheme_guidelines_maintype');
Route::get('/delete_scheme_guidelines_maintype/{id}',[MainSchemeGuidelinesController::class,'delete_scheme_guidelines_maintype'])->name('delete_scheme_guidelines_maintype');
Route::get('/edit_schemeGuidelinesTable/{id}',[MainSchemeGuidelinesController::class,'edit_schemeGuidelinesTable'])->name('edit_schemeGuidelinesTable');
Route::post('/update_main_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'update_main_scheme_guidelines'])->name('update_main_scheme_guidelines');
Route::get('/view_scheme_guidelines_sub/{id}',[MainSchemeGuidelinesController::class,'view_scheme_guidelines_sub'])->name('view_scheme_guidelines_sub');
Route::get('/delete_scheme_guidelines_sub/{id}',[MainSchemeGuidelinesController::class,'delete_scheme_guidelines_sub'])->name('delete_scheme_guidelines_sub');
Route::get('/delete_schemeGuidelinestbl/{id}',[MainSchemeGuidelinesController::class,'delete_schemeGuidelinestbl'])->name('delete_schemeGuidelinestbl');
Route::get('/add_below_new_schemeGuidelinestbl/{schId}/{id}',[MainSchemeGuidelinesController::class,'add_below_new_schemeGuidelinestbl'])->name('add_below_new_schemeGuidelinestbl');
Route::post('/add_new_schemeGuidelinestbl',[MainSchemeGuidelinesController::class,'add_new_schemeGuidelinestbl'])->name('add_new_schemeGuidelinestbl');
Route::get('/delete_scheme_guidelines_footnote/{id}',[MainSchemeGuidelinesController::class,'delete_scheme_guidelines_footnote'])->name('delete_scheme_guidelines_footnote');
Route::get('/delete_new_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'delete_new_scheme_guidelines'])->name('delete_new_scheme_guidelines');
Route::get('/view_new_scheme_guidelines/{id}',[MainSchemeGuidelinesController::class,'view_new_scheme_guidelines'])->name('view_new_scheme_guidelines');
Route::get('/export_scheme_guidelines_pdf/{id}',[MainSchemeGuidelinesController::class,'export_scheme_guidelines_pdf'])->name('export_scheme_guidelines_pdf');


Route::get('/get_manuals/{id}',[ManualsController::class, 'index'])->name('get_manuals');
Route::get('/new_manuals/{id}',[ManualsController::class, 'create'])->name('new_manuals');
Route::post('/store_manuals',[ManualsController::class, 'store'])->name('store_manuals');
Route::post('/update_manuals_pdf/{id}',[ManualsController::class, 'update'])->name('update_manuals_pdf');
Route::get('/view_manuals/{id}',[ManualsController::class, 'show'])->name('view_manuals');
Route::get('/edit_manuals/{id}',[ManualsController::class, 'edit'])->name('edit_manuals');
Route::post('/update_manuals/{id}',[ManualsController::class, 'update_manuals'])->name('update_manuals');

Route::get('/get_orders/{id}',[MainOrderController::class,'index'])->name('get_orders');
Route::get('/new_order/{id}',[MainOrderController::class,'new_order'])->name('new_order');
Route::post('/store_new_order',[MainOrderController::class,'store_new_order'])->name('store_new_order');
Route::get('/edit_new_order/{id}',[MainOrderController::class,'edit_new_order'])->name('edit_new_order');
Route::post('/update_new_order/{id}',[MainOrderController::class,'update_new_order'])->name('update_new_order');
Route::get('/add_order/{id}',[MainOrderController::class,'create'])->name('add_order');
Route::post('/store_order/{id}',[MainOrderController::class,'store'])->name('store_order');
Route::get('/add_below_new_order_maintype/{orderId}/{id}',[MainOrderController::class,'add_below_new_order_maintype'])->name('add_below_new_order_maintype');
Route::post('/store_order_maintype',[MainOrderController::class,'store_order_maintype'])->name('store_order_maintype');
Route::get('/delete_order_maintype/{id}',[MainOrderController::class,'delete_order_maintype'])->name('delete_order_maintype');
Route::get('/edit_orderTable/{id}',[MainOrderController::class,'edit_orderTable'])->name('edit_orderTable');
Route::post('/update_main_order/{id}',[MainOrderController::class,'update_main_order'])->name('update_main_order');
Route::get('/view_order_sub/{id}',[MainOrderController::class,'view_order_sub'])->name('view_order_sub');
Route::get('/delete_order_sub/{id}',[MainOrderController::class,'delete_order_sub'])->name('delete_order_sub');
Route::get('/delete_orderstbl/{id}',[MainOrderController::class,'delete_orderstbl'])->name('delete_orderstbl');
Route::get('/add_below_new_ordertbl/{odrMId}/{id}',[MainOrderController::class,'add_below_new_ordertbl'])->name('add_below_new_ordertbl');
Route::post('/add_new_ordertbl',[MainOrderController::class,'add_new_ordertbl'])->name('add_new_ordertbl');
Route::get('/delete_new_order/{id}',[MainOrderController::class,'delete_new_order'])->name('delete_new_order');
Route::get('/delete_order_footnote/{id}',[MainOrderController::class,'delete_order_footnote'])->name('delete_order_footnote');
Route::get('/view_new_order/{id}',[MainOrderController::class,'view_new_order'])->name('view_new_order');
Route::get('/export_order_pdf/{id}',[MainOrderController::class,'export_order_pdf'])->name('export_order_pdf');


