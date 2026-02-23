<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repository\Movie\EloquentMovieRepository;
use App\Repository\Movie\MovieRepository;
use App\Repository\Series\EloquentSerieRepository;
use App\Repository\Series\SerieRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            MovieRepository::class,
            EloquentMovieRepository::class
        );
        $this->app->bind(
            SerieRepository::class,
            EloquentSerieRepository::class
        );
    }
}
