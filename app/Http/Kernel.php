<?php

namespace App\Http;

use App\Http\Middleware\SetLocaleFromHeader;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            SubstituteBindings::class,
            SetLocaleFromHeader::class,
        ],

        'api' => [
            SubstituteBindings::class,

            SetLocaleFromHeader::class,
        ],
    ];

    protected $routeMiddleware = [
        'locale' => SetLocaleFromHeader::class,
    ];
}
