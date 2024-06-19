
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('blog/posts', [PostController::class, 'index']);


//categories
Route::get('blog/categories', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'index']);
Route::get('blog/categories/{id}', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'show']);
Route::post('blog/categories', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'create']);
Route::put('blog/categories/{id}', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'update']);
