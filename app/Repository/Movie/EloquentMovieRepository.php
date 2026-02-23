<?php

declare(strict_types=1);

namespace App\Repository\Movie;

use App\Exceptions\MovieNotFoundException;
use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentMovieRepository implements MovieRepository
{
    public function getPopular(int $perPage = 20): LengthAwarePaginator
    {
        return Movie::with([
            'translations.language',
            'genres.translations.language'
        ])
            ->orderByDesc('popularity')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Movie
    {
        $movie = Movie::with([
            'translations.language',
            'genres.translations.language'
        ])->find($id);

        if (!$movie) {
            throw new MovieNotFoundException('Movie not found');
        }

        return $movie;
    }
}
