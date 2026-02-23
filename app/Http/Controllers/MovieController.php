<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Http\Resources\MovieResource;
use App\Repository\Movie\MovieRepository;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{

    public function __construct(
        private MovieRepository $repository
    ) {}

    public function list(ListRequest $request): JsonResponse
    {
        $movies = $this->repository->getPopular($request->getPerPage());

        return MovieResource::collection($movies)->response();
    }

    public function show(int $id): JsonResponse
    {
        $movie = $this->repository->findById($id);

        return (new MovieResource($movie))->response();
    }
}
