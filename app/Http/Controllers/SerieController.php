<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Http\Resources\SerieResource;
use App\Services\SerieService;
use Illuminate\Http\JsonResponse;

class SerieController extends Controller
{
    public function __construct(
        private SerieService $service
    ) {}

    public function list(ListRequest $request): JsonResponse
    {
        $series = $this->service->listPopular($request->getPerPage());

        return SerieResource::collection($series)->response();
    }

    public function show(string $id): JsonResponse
    {
        $serie = $this->service->findById($id);

        if (!$serie) {
            return response()->json([
                'message' => 'Serie not found'
            ], 404);
        }

        return (new SerieResource($serie))->response();
    }
}
