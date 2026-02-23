<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreTranslation extends Model
{
    use HasFactory;

    protected $table = 'genre_translations';

    protected $fillable = [
        'genre_id',
        'language_id',
        'title',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
