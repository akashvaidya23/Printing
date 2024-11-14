<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

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
    $orders = Order::selectRaw('DATE(created_at) as order_date, COUNT(id) as total_orders, SUM(total_products) as total_products, SUM(total_amount) as total_amount')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->paginate(100);
    return view('welcome',compact('orders'));
});

Route::resource("product",ProductController::class);
Route::resource("billing",BillingController::class);
Route::resource("order",OrderController::class);
Route::post("/product/search",[ProductController::class,'search'])->name('product_search');
Route::post("/product/options",[ProductController::class,'search_options'])->name('search_options');
Route::post("/add/product",[BillingController::class,"add_product"])->name("add_product");
Route::post('/generate-pdf', [BillingController::class, 'generatePDF'])->name('generate.pdf');
Route::get('/generate-pdf/{id}', [BillingController::class, 'generatePDF_1'])->name('generateInvoice');
Route::get('dashboard',[BillingController::class,'dashboard'])->name('dashboard');
Route::get('/dashboard/get/{start_date}/{end_date}',[BillingController::class,'getDateFilter'])->name('dashboard_get');
Route::get('/getOrderPayments/{id}',[BillingController::class,'getOrderPayments'])->name('getOrderPayments');
Route::post('/addOrderPayment',[BillingController::class,'addOrderPayment'])->name('addOrderPayment');