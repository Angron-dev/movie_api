<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerieTranslation extends Model
{
    use HasFactory;

    protected $table = 'serie_translations';

    protected $fillable = [
        'series_id',
        'language_id',
        'title',
        'overview'
    ];

    public function serie()
    {
        return $this->belongsTo(Serie::class, 'series_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
