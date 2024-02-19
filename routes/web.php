<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DataController;
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

Route::post('/SalesInvoiceImport', [SalesController::class, 'SalesInvoiceImport'])->name('SalesInvoiceImport');

Route::get('/', [DataController::class, 'index']);
Route::any('/sales/data', [DataController::class, 'fetchSalesData'])->name('sales.data');
