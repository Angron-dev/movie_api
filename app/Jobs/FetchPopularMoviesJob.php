<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchPopularMoviesJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public int $limit = 50
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
                        'https://api.themoviedb.org/3/movie/popular',
                        [
                            'api_key' => $apiKey,
                            'page' => $page
                        ]
                    );

                if (!$response->ok()) {
                    logger()->warning('TMDB request failed', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);

                    break;
                }

                $movies = $response->json('results', []);

            } catch (\Exception $exception) {
                logger()->error('TMDB sync exception', [
                    'page' => $page,
                    'error' => $exception->getMessage()
                ]);
                break;
            }
            foreach ($movies as $movie) {

                if ($collected >= $this->limit) {
                    break 2;
                }

                HydrateMovieJob::dispatch($movie['id']);

                $collected++;
            }

            $page++;
        }
    }
}
