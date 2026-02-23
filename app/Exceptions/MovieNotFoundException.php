<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class MovieNotFoundException extends Exception
{
    public function render($request):JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 404);
    }
}
