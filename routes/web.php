<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/search', function () {
    return Inertia::render('SearchMovies');
})->name('search');

Route::get('/favorites', function () {
    return Inertia::render('Favorites');
})->name('favorites');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
