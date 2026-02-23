<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';

    protected $keyType = 'int';

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
        return $this->belongsToMany(
            Genre::class,
            'movie_genre',
            'movie_id',
            'genre_id'
        );
    }

    public function translations()
    {
        return $this->hasMany(
            MovieTranslation::class,
            'movies_id'
        );
    }
}
