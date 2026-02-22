<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        $translation = $this->translations
            ->where('language.code', $locale)
            ->first()
            ?? $this->translations
                ->where('language.code', 'en')
                ->first();

        return [
            'id' => $this->id,
            'title' => $translation?->title ?? $this->title,
            'overview' => $translation?->overview ?? $this->overview,
            'vote_average' => $this->vote_average,
            'vote_count' => $this->vote_count,
            'popularity' => $this->popularity,
            'release_date' => $this->release_date,
            'poster_path' => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'tmdb_id' => $this->tmdb_id,
            'genres' => $this->genres->map(function ($genre) use ($locale) {

                $translation = $genre->translations
                    ->where('language.code', $locale)
                    ->first()
                    ?? $genre->translations
                        ->where('language.code', 'en')
                        ->first();

                return [
                    'id' => $genre->id,
                    'name' => $translation?->title ?? null
                ];
            })
        ];
    }
}
