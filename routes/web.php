<?php

use App\Http\Controllers\HkiController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('products/create', [ProductController::class, 'create'])->name('product.create');
Route::post('products', [ProductController::class, 'store'])->name('product.store');
Route::get('products/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('products/{product}', [ProductController::class, 'update'])->name('product.update');
Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('product.destroy'); 


//dosen hki
Route::get('hki', [HkiController::class, 'index'])->name('hki.index');
Route::get('hki/create',[HkiController::class, 'create'])->name('hki.create');
Route::post('hki', [HkiController::class, 'store'])->name('hki.store');
Route::get('hki/{hki}/edit', [HkiController::class, 'edit'])->name('hki.edit');
Route::put('hki/{hki}', [HkiController::class, 'update'])->name('hki.update');
Route::get('hki/{hki}', [HkiController::class, 'show'])->name('hki.show');
Route::delete('hki/{hki}',[HkiController::class, 'destroy'])->name('hki.destroy');
Route::get('hki/{hki}/cetak', [HkiController::class, 'cetak'])->name('hki.cetak');
Route::get('hki/{hki}/downloadWord',[HkiController::class,'downloadWord'])->name('hki.downloadWord');