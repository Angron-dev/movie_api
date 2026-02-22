<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\GenreTranslation;
use App\Models\Language;
use App\Models\Serie;
use App\Models\SerieTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SerieApiTest extends TestCase
{
    use RefreshDatabase;

    private Language $english;
    private Language $polish;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.supported_locales' => ['en', 'pl']]);

        $this->english = Language::create(['name' => 'English', 'code' => 'en']);
        $this->polish = Language::create(['name' => 'Polish', 'code' => 'pl']);
    }

    private function createSerie(float $popularity = 10.0): Serie
    {
        $serie = Serie::create([
            'tmdb_id' => fake()->unique()->numberBetween(1000, 99999),
            'poster_path' => '/poster.jpg',
            'backdrop_path' => '/backdrop.jpg',
            'release_date' => '2025-01-01',
            'vote_average' => 8.5,
            'vote_count' => 100,
            'popularity' => $popularity,
        ]);

        SerieTranslation::create([
            'series_id' => $serie->id,
            'language_id' => $this->english->id,
            'title' => 'English Title',
            'overview' => 'English Overview',
        ]);

        SerieTranslation::create([
            'series_id' => $serie->id,
            'language_id' => $this->polish->id,
            'title' => 'Polski Tytuł',
            'overview' => 'Polski Opis',
        ]);

        $genre = Genre::create(['id' => fake()->unique()->numberBetween(1, 99999)]);
        GenreTranslation::create([
            'genre_id' => $genre->id,
            'language_id' => $this->english->id,
            'title' => 'Action',
        ]);
        GenreTranslation::create([
            'genre_id' => $genre->id,
            'language_id' => $this->polish->id,
            'title' => 'Akcja',
        ]);

        $serie->genres()->attach($genre->id);

        return $serie;
    }

    public function test_list_returns_200(): void
    {
        $this->createSerie();

        $response = $this->getJson('/api/series');

        $response->assertStatus(200);
    }

    public function test_list_returns_paginated_data(): void
    {
        $this->createSerie(20.0);
        $this->createSerie(10.0);

        $response = $this->getJson('/api/series');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'overview', 'genres'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_list_respects_per_page_parameter(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->createSerie((float) $i);
        }

        $response = $this->getJson('/api/series?per_page=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_list_validates_per_page_min(): void
    {
        $response = $this->getJson('/api/series?per_page=2');

        $response->assertStatus(422);
    }

    public function test_list_validates_per_page_max(): void
    {
        $response = $this->getJson('/api/series?per_page=100');

        $response->assertStatus(422);
    }

    public function test_list_orders_by_popularity_desc(): void
    {
        $less = $this->createSerie(5.0);
        $more = $this->createSerie(50.0);

        $response = $this->getJson('/api/series');

        $response->assertStatus(200);

        $ids = collect($response->json('data'))->pluck('id')->toArray();
        $this->assertEquals([$more->id, $less->id], $ids);
    }

    public function test_list_returns_english_translation_by_default(): void
    {
        $this->createSerie();

        $response = $this->getJson('/api/series');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'English Title')
            ->assertJsonPath('data.0.overview', 'English Overview')
            ->assertJsonPath('data.0.genres.0.name', 'Action');
    }

    public function test_list_returns_polish_translation_with_accept_language(): void
    {
        $this->createSerie();

        $response = $this->getJson('/api/series', ['Accept-Language' => 'pl']);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Polski Tytuł')
            ->assertJsonPath('data.0.overview', 'Polski Opis')
            ->assertJsonPath('data.0.genres.0.name', 'Akcja');
    }

    public function test_list_falls_back_to_english_for_unsupported_locale(): void
    {
        $this->createSerie();

        $response = $this->getJson('/api/series', ['Accept-Language' => 'de']);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'English Title');
    }

    public function test_show_returns_serie(): void
    {
        $serie = $this->createSerie();

        $response = $this->getJson("/api/series/{$serie->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'overview', 'genres'],
            ])
            ->assertJsonPath('data.id', $serie->id)
            ->assertJsonPath('data.title', 'English Title');
    }

    public function test_show_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/series/99999');

        $response->assertStatus(404)
            ->assertJsonPath('message', 'Serie not found');
    }

    public function test_show_returns_polish_translation(): void
    {
        $serie = $this->createSerie();

        $response = $this->getJson("/api/series/{$serie->id}", ['Accept-Language' => 'pl']);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Polski Tytuł')
            ->assertJsonPath('data.genres.0.name', 'Akcja');
    }
}
