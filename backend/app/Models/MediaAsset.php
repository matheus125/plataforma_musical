<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'type',
        'disk',
        'bucket',
        'storage_key',
        'public_url',
        'mime_type',
        'size_bytes',
        'checksum',
        'duration_ms',
        'sample_rate',
        'channels',
        'bitrate',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'size_bytes' => 'integer',
            'duration_ms' => 'integer',
            'sample_rate' => 'integer',
            'channels' => 'integer',
            'bitrate' => 'integer',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }
}
