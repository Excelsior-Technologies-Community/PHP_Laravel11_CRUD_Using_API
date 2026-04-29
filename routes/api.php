<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use Faker\Guesser\Name;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all API routes for products are defined.
| We are using descriptive method names for clarity.
| All routes use GET/POST methods so you can test in Postman easily.
|
*/

// Get all products
Route::get('products', [ProductApiController::class, 'getAllProducts'])->name('products.allLists');



// Get single product by ID
Route::get('products/{id}', [ProductApiController::class, 'getProduct'])->name('products.list');

// Create new product
Route::post('products/add', [ProductApiController::class, 'addProduct'])->name('products.add');

// Fetch product data for editing
Route::get('products/edit/{id}', [ProductApiController::class, 'editProduct'])->name('products.edit');

// Update product
Route::post('products/update/{id}', [ProductApiController::class, 'updateProduct'])->name('products.update');

// Soft delete product
Route::post('products/delete/{id}', [ProductApiController::class, 'softDeleteProduct'])->name('products.delete');

// Category routes
Route::get('categories', [CategoryApiController::class, 'getAllCategories']);
Route::get('categories/{id}', [CategoryApiController::class, 'getCategory']);
Route::post('categories/add', [CategoryApiController::class, 'addCategory']);
Route::get('categories/edit/{id}', [CategoryApiController::class, 'editCategory']);
Route::post('categories/update/{id}', [CategoryApiController::class, 'updateCategory']);
Route::post('categories/delete/{id}', [CategoryApiController::class, 'softDeleteCategory']);