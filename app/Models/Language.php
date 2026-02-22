<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';
    protected $fillable = ['name', 'code'];

    public function movieTranslations()
    {
        return $this->hasMany(MovieTranslation::class);
    }

    public function genreTranslations()
    {
        return $this->hasMany(GenreTranslation::class);
    }
}
