<?php

namespace App\Repository;

use App\Models\Serie;
use Illuminate\Pagination\LengthAwarePaginator;

class SerieRepository
{
    public function getPopular(int $perPage = 20):LengthAwarePaginator
    {
        return Serie::with([
            'translations.language',
            'genres.translations.language'
        ])
            ->orderByDesc('popularity')
            ->paginate($perPage);
    }

    public function findById(string $id): ?Serie
    {
        return Serie::with([
            'translations.language',
            'genres.translations.language'
        ])
            ->where('id', (int)$id)
            ->first();
    }
}
