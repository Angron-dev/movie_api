<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = 'series';
    protected $fillable = [
        'tmdb_id',
        'poster_path',
        'backdrop_path',
        'release_date',
        'vote_average',
        'vote_count',
        'popularity'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'serie_genre', 'serie_id', 'genre_id');
    }

    public function translations()
    {
        return $this->hasMany(
            SerieTranslation::class,
            'series_id'
        );
    }
}
