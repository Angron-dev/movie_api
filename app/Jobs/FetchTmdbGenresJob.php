<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Genre;
use App\Models\GenreTranslation;
use App\Models\Language;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class FetchTmdbGenresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(): void
    {
        $apiKey = config('app.tmdb_api_key');
        $languages = Language::all();

        foreach ($languages as $language) {

            foreach (['movie', 'tv'] as $type) {

                $response = Http::get(
                    "https://api.themoviedb.org/3/genre/{$type}/list",
                    [
                        'api_key' => $apiKey,
                        'language' => $language->code,
                    ]
                );

                if (!$response->ok()) {
                    continue;
                }

                $this->saveGenres(
                    $response->json('genres', []),
                    $language
                );
            }
        }
    }

    private function saveGenres(array $genres, Language $language): void
    {
        foreach ($genres as $genreData) {
            $genre = Genre::firstOrCreate([
                'id' => $genreData['id'],
            ]);

            GenreTranslation::updateOrCreate(
                [
                    'genre_id' => $genre->id,
                    'language_id' => $language->id,
                ],
                [
                    'title' => $genreData['name'],
                ]
            );
        }
    }
}
