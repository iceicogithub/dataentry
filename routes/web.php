<?php

use App\Http\Controllers\ActController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SectionController;
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

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/category', [CategoryController::class, 'index'])->name('category');
Route::get('/act', [ActController::class, 'index'])->name('act');
Route::get('/add-act', [ActController::class, 'create'])->name('add-act');
Route::get('/section', [SectionController::class, 'index'])->name('section');
Route::get('/add-section', [SectionController::class, 'create'])->name('add-section');
Route::get('/sub-section', [SectionController::class, 'SubSection_Index'])->name('sub-section');
Route::get('/add-sub-section', [SectionController::class, 'SubSection_Create'])->name('add-sub-section');
Route::get('/chapter', [ChapterController::class, 'index'])->name('chapter');
Route::get('/add-chapter', [ChapterController::class, 'create'])->name('add-chapter');
