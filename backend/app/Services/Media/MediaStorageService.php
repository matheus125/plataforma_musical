<?php

namespace App\Services\Media;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaStorageService
{
    public function storePerformanceAudio(UploadedFile $file, User $owner): MediaAsset
    {
        $path = $file->store('performances', 'public');

        return MediaAsset::create([
            'owner_id' => $owner->id,
            'type' => 'performance_audio',
            'disk' => 'public',
            'storage_key' => $path,
            'public_url' => Storage::disk('public')->url($path),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'checksum' => hash_file('sha256', $file->getRealPath()),
            'status' => 'uploaded',
            'metadata' => [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
            ],
        ]);
    }
}
