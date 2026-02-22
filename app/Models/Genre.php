<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';

    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id'];


    public function movies()
    {
        return $this->belongsToMany(
            Movie::class,
            'movie_genre',
            'genre_id',
            'movie_id'
        );
    }

    public function series()
    {
        return $this->belongsToMany(
            Serie::class,
            'series_genre',
            'genre_id',
            'series_id'
        );
    }

    public function translations()
    {
        return $this->hasMany(GenreTranslation::class);
    }

    public function translation(string $code)
    {
        return $this->hasOne(GenreTranslation::class)
            ->whereHas('language', fn ($q) => $q->where('code', $code));
    }
}
