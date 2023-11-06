<?php

use App\Http\Controllers\ActController;
use App\Http\Controllers\CategoryController;
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
