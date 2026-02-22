<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Http\Resources\MovieResource;
use App\Services\MovieService;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function __construct(
        private MovieService $service
    ) {}

    public function list(ListRequest $request): JsonResponse
    {
        $movies = $this->service->listPopular($request->getPerPage());

        return MovieResource::collection($movies)->response();
    }

    public function show(string $id): JsonResponse
    {
        $movie = $this->service->findById($id);

        if (!$movie) {
            return response()->json([
                'message' => 'Movie not found'
            ], 404);
        }

        return (new MovieResource($movie))->response();
    }
}
