<?php

namespace App\Services;

use App\Models\Serie;
use App\Repository\SerieRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class SerieService
{
    public function __construct(
        private SerieRepository $repository
    ) {}

    public function listPopular(int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->getPopular($perPage);
    }

    public function findById(string $id): ?Serie
    {
        return $this->repository->findById($id);
    }
}
