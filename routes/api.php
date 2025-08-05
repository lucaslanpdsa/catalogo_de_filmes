<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FavoriteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('favorites', [FavoriteController::class, 'index']);
Route::post('favorites', [FavoriteController::class, 'store']);
Route::delete('favorites/{id}', [FavoriteController::class, 'destroy']);