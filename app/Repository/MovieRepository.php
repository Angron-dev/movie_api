<?php

namespace App\Repository;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieRepository
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

    public function findById(string $id): ?Movie
    {
        return Movie::with([
            'translations.language',
            'genres.translations.language'
        ])
            ->where('id', $id)
            ->first();
    }
}
