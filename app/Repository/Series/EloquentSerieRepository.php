<?php

declare(strict_types=1);

namespace App\Repository\Series;

use App\Exceptions\SerieNotFoundException;
use App\Models\Serie;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentSerieRepository implements SerieRepository
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

    public function findById(int $id): ?Serie
    {
        $serie = Serie::with([
            'translations.language',
            'genres.translations.language'
        ])
            ->find($id);

        if (!$serie) {
            throw new SerieNotFoundException('Series not found');
        }

        return $serie;
    }
}
