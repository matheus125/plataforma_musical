<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingPeriod extends Model
{
    protected $fillable = [
        'type',
        'scope',
        'country',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function entries()
    {
        return $this->hasMany(RankingEntry::class);
    }
}
