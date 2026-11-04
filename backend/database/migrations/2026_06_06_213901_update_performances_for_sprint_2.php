<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            if (! Schema::hasColumn('performances', 'audio_url')) {
                $table->string('audio_url', 2048)->nullable()->after('song_id');
            }

            if (! Schema::hasColumn('performances', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->nullable()->after('audio_url');
            }

            if (! Schema::hasColumn('performances', 'score_overall')) {
                $table->unsignedTinyInteger('score_overall')->nullable()->after('duration_seconds');
            }

            if (! Schema::hasColumn('performances', 'score_pitch')) {
                $table->unsignedTinyInteger('score_pitch')->nullable()->after('score_overall');
            }

            if (! Schema::hasColumn('performances', 'score_rhythm')) {
                $table->unsignedTinyInteger('score_rhythm')->nullable()->after('score_pitch');
            }

            if (! Schema::hasColumn('performances', 'likes_count')) {
                $table->unsignedInteger('likes_count')->default(0)->after('score_rhythm');
            }

            if (! Schema::hasColumn('performances', 'views_count')) {
                $table->unsignedInteger('views_count')->default(0)->after('likes_count');
            }

            if (! Schema::hasColumn('performances', 'is_public')) {
                $table->boolean('is_public')->default(true)->after('views_count');
            }

            $table->index(['is_public', 'created_at'], 'performances_public_created_at_index');
        });

        Schema::table('performances', function (Blueprint $table) {
            foreach (['title', 'media_url', 'notes', 'status', 'score', 'submitted_at'] as $column) {
                if (Schema::hasColumn('performances', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->dropIndex('performances_public_created_at_index');
            $table->dropColumn([
                'audio_url',
                'duration_seconds',
                'score_overall',
                'score_pitch',
                'score_rhythm',
                'likes_count',
                'views_count',
                'is_public',
            ]);
        });
    }
};
