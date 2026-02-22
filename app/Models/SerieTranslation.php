<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerieTranslation extends Model
{
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
