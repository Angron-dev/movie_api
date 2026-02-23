<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Http\Resources\SerieResource;
use App\Repository\Series\SerieRepository;
use Illuminate\Http\JsonResponse;

class SerieController extends Controller
{
    public function __construct(
        private SerieRepository $repository
    ) {}

    public function list(ListRequest $request): JsonResponse
    {
        $series = $this->repository->getPopular($request->getPerPage());

        return SerieResource::collection($series)->response();
    }

    public function show(int $id): JsonResponse
    {
        $serie = $this->repository->findById($id);

        return (new SerieResource($serie))->response();
    }
}
