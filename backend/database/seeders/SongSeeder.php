<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $songs = [
            [
                'title' => 'Neon Pulse',
                'artist' => 'MVP Session Band',
                'genre' => 'Pop Rock',
                'language' => 'pt-BR',
                'key' => 'Am',
                'bpm' => 118,
                'difficulty_level' => 1,
                'cover_image_url' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Midnight Groove',
                'artist' => 'Blue Stage Collective',
                'genre' => 'Funk',
                'language' => 'en',
                'key' => 'Dm',
                'bpm' => 104,
                'difficulty_level' => 3,
                'cover_image_url' => 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Purple Skyline',
                'artist' => 'Arena Synths',
                'genre' => 'Synthwave',
                'language' => 'pt-BR',
                'key' => 'F#m',
                'bpm' => 126,
                'difficulty_level' => 3,
                'cover_image_url' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Velocity Solo',
                'artist' => 'Electric Quarter',
                'genre' => 'Rock',
                'language' => 'en',
                'key' => 'E',
                'bpm' => 142,
                'difficulty_level' => 4,
                'cover_image_url' => 'https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Final Chorus',
                'artist' => 'Competitive Choir Lab',
                'genre' => 'Vocal',
                'language' => 'pt-BR',
                'key' => 'C',
                'bpm' => 92,
                'difficulty_level' => 5,
                'cover_image_url' => 'https://images.unsplash.com/photo-1521337581100-8ca9a73a5f79?auto=format&fit=crop&w=900&q=80',
            ],
        ];

        foreach ($songs as $song) {
            Song::updateOrCreate(
                ['title' => $song['title'], 'artist' => $song['artist']],
                $song + ['is_active' => true],
            );
        }
    }
}
