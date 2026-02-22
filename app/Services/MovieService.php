<?php

namespace App\Services;

use App\Models\Movie;
use App\Repository\MovieRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieService
{
    public function __construct(
        private MovieRepository $repository
    ) {}

    public function listPopular(int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->getPopular($perPage);
    }

    public function findById(string $id): ?Movie
    {
        return $this->repository->findById($id);
    }
}
