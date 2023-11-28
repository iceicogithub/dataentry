<?php

use App\Http\Controllers\ActController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SectionController;
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

Route::get('section-list', function () {
    return view('admin.section.index');
})->name('section-list');

Route::get('edit-section', function () {
    return view('admin.section.edit');
})->name('edit-section');

Route::get('/category', [CategoryController::class, 'index'])->name('category');

Route::get('/act', [ActController::class, 'index'])->name('act');
Route::get('/add-act', [ActController::class, 'create'])->name('add-act');
Route::get('/edit-act', [ActController::class, 'edit'])->name('edit-act');
Route::post('/store_act', [ActController::class, 'store']);

Route::get('/section', [SectionController::class, 'index'])->name('section');
Route::get('/add-section', [SectionController::class, 'create'])->name('add-section');
Route::get('/sub-section', [SectionController::class, 'SubSection_Index'])->name('sub-section');
Route::get('/add-sub-section', [SectionController::class, 'SubSection_Create'])->name('add-sub-section');
Route::get('/chapter', [ChapterController::class, 'index'])->name('chapter');
Route::get('/add-chapter', [ChapterController::class, 'create'])->name('add-chapter');
