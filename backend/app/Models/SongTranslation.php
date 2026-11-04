<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongTranslation extends Model
{
    protected $fillable = [
        'song_id',
        'locale',
        'title',
        'artist_display',
        'genre_display',
        'lyrics',
    ];
}
