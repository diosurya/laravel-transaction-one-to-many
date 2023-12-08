<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SyncProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('product', ProductController::class);
    Route::get('/products/data', [ProductController::class, 'dataTable'])->name('product.dataTable');

    Route::get('/sync', [SyncProductController::class, 'index'])->name('sync.index');
    Route::get('/sync/getData', [SyncProductController::class, 'getData'])->name('sync.getData');
    Route::get('/sync/sync', [SyncProductController::class, 'sync'])->name('sync.sync');

    Route::resource('transaction', TransactionController::class);
    Route::get('/transaction/data', [TransactionController::class, 'dataTable'])->name('transaction.dataTable');
    Route::put('/transaction/editproduct/{id}', [TransactionController::class, 'editProduct'])->name('transaction.editProduct');
    Route::delete('/transaction/deleteproduct/{id}', [TransactionController::class, 'deleteProduct'])->name('transaction.deleteProduct');
});

Auth::routes();
