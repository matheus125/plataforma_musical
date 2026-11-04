<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            if (! Schema::hasColumn('songs', 'language')) {
                $table->string('language', 40)->nullable()->after('genre');
            }

            if (! Schema::hasColumn('songs', 'key')) {
                $table->string('key', 12)->nullable()->after('language');
            }

            if (! Schema::hasColumn('songs', 'bpm')) {
                $table->unsignedSmallInteger('bpm')->nullable()->after('key');
            }

            if (! Schema::hasColumn('songs', 'difficulty_level')) {
                $table->unsignedTinyInteger('difficulty_level')->default(3)->after('bpm');
            }

            if (! Schema::hasColumn('songs', 'audio_url')) {
                $table->string('audio_url', 2048)->nullable()->after('difficulty_level');
            }

            if (! Schema::hasColumn('songs', 'cover_image_url')) {
                $table->string('cover_image_url', 2048)->nullable()->after('audio_url');
            }
        });

        if (Schema::hasColumn('songs', 'difficulty')) {
            DB::table('songs')->where('difficulty', 'beginner')->update(['difficulty_level' => 1]);
            DB::table('songs')->where('difficulty', 'intermediate')->update(['difficulty_level' => 3]);
            DB::table('songs')->where('difficulty', 'advanced')->update(['difficulty_level' => 4]);
            DB::table('songs')->where('difficulty', 'expert')->update(['difficulty_level' => 5]);
        }

        Schema::table('songs', function (Blueprint $table) {
            $table->index(['is_active', 'difficulty_level'], 'songs_active_difficulty_level_index');
        });
    }

    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropIndex('songs_active_difficulty_level_index');
            $table->dropColumn([
                'language',
                'key',
                'bpm',
                'difficulty_level',
                'audio_url',
                'cover_image_url',
            ]);
        });
    }
};
