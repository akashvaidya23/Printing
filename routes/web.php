<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
    return view('welcome');
});

Route::resource("product",ProductController::class);
Route::resource("billing",BillingController::class);
Route::post("/product/search",[ProductController::class,'search'])->name('product_search');
Route::post("/product/options",[ProductController::class,'search_options'])->name('search_options');