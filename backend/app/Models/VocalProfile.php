<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocalProfile extends Model
{
    protected $fillable = [
        'user_id',
        'vocal_range_min',
        'vocal_range_max',
        'comfortable_key',
        'voice_type',
        'current_level',
        'goals',
    ];

    protected function casts(): array
    {
        return [
            'goals' => 'array',
            'current_level' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
