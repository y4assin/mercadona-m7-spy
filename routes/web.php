<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/import-categories', [CategoryController::class, 'importarCategories']);
Route::get('/import-products', [ProductController::class, 'importarProductePerCategoria']);

Route::get('/', function () {
    return view('welcome');
});
