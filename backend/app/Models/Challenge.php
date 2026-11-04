<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Challenge extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'type',
        'rules',
        'country',
        'locale',
        'starts_at',
        'ends_at',
        'status',
        'is_premium',
    ];

    protected function casts(): array
    {
        return [
            'rules' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_premium' => 'boolean',
        ];
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'challenge_songs');
    }
}
