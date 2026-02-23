<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieTranslation extends Model
{
    use HasFactory;

    protected $table = 'movie_translations';

    protected $fillable = [
        'movies_id',
        'language_id',
        'title',
        'overview'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movies_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
