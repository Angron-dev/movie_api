<?php

declare(strict_types=1);

namespace App\Repository\Series;

use App\Models\Serie;
use Illuminate\Pagination\LengthAwarePaginator;

interface SerieRepository
{
    public function getPopular(int $perPage = 20): LengthAwarePaginator;
    public function findById(int $id): ?Serie;
}
