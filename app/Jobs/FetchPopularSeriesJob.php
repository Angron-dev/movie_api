<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchPopularSeriesJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public int $limit = 10
    )
    {
    }

    public function handle(): void
    {
        $apiKey = config('app.tmdb_api_key');

        $page = 1;
        $collected = 0;

        while ($collected < $this->limit) {

            try {

                $response = Http::timeout(10)
                    ->retry(3, 500)
                    ->get(
                        'https://api.themoviedb.org/3/tv/popular',
                        [
                            'api_key' => $apiKey,
                            'page' => $page,
                        ]
                    );

                if (!$response->ok()) {

                    logger()->warning('TMDB popular series request failed', [
                        'status' => $response->status(),
                        'page' => $page
                    ]);

                    return;
                }

                $series = $response->json('results', []);

                if (!is_array($series)) {
                    return;
                }

            } catch (\Throwable $e) {

                logger()->error('TMDB popular series sync exception', [
                    'page' => $page,
                    'error' => $e->getMessage()
                ]);

                return;
            }

            foreach ($series as $serie) {

                if ($collected >= $this->limit) {
                    return;
                }

                HydrateSerieJob::dispatch($serie['id']);

                $collected++;
            }

            $page++;
        }
    }
}
