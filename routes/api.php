<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\SerieController;
use Illuminate\Support\Facades\Route;

Route::middleware(['locale'])->group(function () {

    Route::prefix('series')->group(function () {

        Route::get('/', [SerieController::class, 'list'])
            ->name('api.series.list');

        Route::get('/{id}', [SerieController::class, 'show'])
            ->name('api.series.single');
    });

    Route::prefix('movies')->group(function () {

        Route::get('/', [MovieController::class, 'list'])
            ->name('api.movies.list');

        Route::get('/{id}', [MovieController::class, 'show'])
            ->name('api.movies.single');
    });

});
