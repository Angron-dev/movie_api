<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\MovieTranslation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class HydrateMovieJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    private array $languages;

    public function __construct(
        public int $tmdbId
    )
    {
        $this->languages = Language::pluck('code')->toArray();
    }

    public function handle(): void
    {
        try {
            $response = Http::timeout(10)
                ->retry(3, 500)
                ->get(
                    "https://api.themoviedb.org/3/movie/{$this->tmdbId}",
                    [
                        'api_key' => config('app.tmdb_api_key'),
                        'append_to_response' => 'translations'
                    ]
                );

            if (!$response->ok()) {

                logger()->warning('TMDB request failed', [
                    'tmdb_id' => $this->tmdbId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return;
            }

            $data = $response->json();

        } catch (\Exception $exception) {
            logger()->error('TMDB sync exception', [
                'tmdb_id' => $this->tmdbId,
                'error' => $exception->getMessage()
            ]);
            return;
        }


        $movie = Movie::updateOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'tmdb_id' => $data['id'],
                'poster_path' => $data['poster_path'],
                'backdrop_path' => $data['backdrop_path'],
                'release_date' => $data['release_date'] ?? null,
                'vote_average' => $data['vote_average'] ?? 0,
                'vote_count' => $data['vote_count'] ?? 0,
                'popularity' => $data['popularity'] ?? 0,
            ]
        );

        $genreIds = collect($data['genres'] ?? [])
            ->pluck('id')
            ->toArray();

        foreach ($genreIds as $genreId) {
            Genre::firstOrCreate(['id' => $genreId]);
        }

        $movie->genres()->sync($genreIds);


        foreach ($data['translations']['translations'] ?? [] as $translation) {

            $langCode = $translation['iso_639_1'];

            if (!in_array($langCode, $this->languages)) {
                continue;
            }

            $languageId = Language::where('code', $langCode)->value('id');

            if (!$languageId) {
                continue;
            }

            $title =
                isset($translation['data']['title']) &&
                trim($translation['data']['title']) !== ''
                    ? $translation['data']['title']
                    : ($data['title'] ?? null);
            $overview =
                isset($translation['data']['overview']) &&
                trim($translation['data']['overview']) !== ''
                    ? $translation['data']['overview']
                    : ($data['overview'] ?? null);

            MovieTranslation::updateOrCreate(
                [
                    'movies_id' => $movie->id,
                    'language_id' => $languageId
                ],
                [
                    'title' => $title,
                    'overview' => $overview,
                ]
            );
        }
    }
}
