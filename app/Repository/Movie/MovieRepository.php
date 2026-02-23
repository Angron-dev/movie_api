<?php

declare(strict_types=1);

namespace App\Repository\Movie;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovieRepository
{
    public function getPopular(int $perPage = 20): LengthAwarePaginator;
    public function findById(int $id): ?Movie;
}
