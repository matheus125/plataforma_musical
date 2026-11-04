<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 40)->default('audio');
            $table->string('disk', 40)->default('public');
            $table->string('bucket')->nullable();
            $table->string('storage_key', 2048);
            $table->string('public_url', 2048)->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('checksum', 128)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->unsignedInteger('sample_rate')->nullable();
            $table->unsignedSmallInteger('channels')->nullable();
            $table->unsignedInteger('bitrate')->nullable();
            $table->string('status', 40)->default('uploaded');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_id', 'type']);
            $table->index(['status', 'created_at']);
        });

        Schema::table('performances', function (Blueprint $table) {
            $table->foreignId('media_asset_id')->nullable()->after('song_id')->constrained('media_assets')->nullOnDelete();
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->string('status', 40)->default('published')->after('audio_url');
            $table->string('visibility', 40)->default('public')->after('status');
            $table->timestamp('published_at')->nullable()->after('visibility');
            $table->timestamp('processing_started_at')->nullable()->after('published_at');
            $table->timestamp('processing_finished_at')->nullable()->after('processing_started_at');
            $table->text('processing_error')->nullable()->after('processing_finished_at');
            $table->string('analysis_version', 40)->nullable()->after('processing_error');
            $table->softDeletes();
            $table->index(['user_id', 'created_at'], 'performances_user_created_at_index');
            $table->index(['song_id', 'score_overall'], 'performances_song_score_index');
            $table->index(['status', 'created_at'], 'performances_status_created_at_index');
        });

        Schema::create('vocal_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('status', 40)->default('pending');
            $table->string('model_name')->nullable();
            $table->string('model_version', 80)->nullable();
            $table->string('analysis_version', 40)->default('v1-placeholder');
            $table->timestamp('analysis_started_at')->nullable();
            $table->timestamp('analysis_finished_at')->nullable();
            $table->unsignedInteger('audio_duration_ms')->nullable();
            $table->unsignedInteger('sample_rate')->nullable();
            $table->unsignedSmallInteger('channels')->nullable();
            $table->unsignedInteger('bitrate')->nullable();
            $table->string('format', 40)->nullable();
            $table->decimal('pitch_accuracy', 5, 2)->nullable();
            $table->decimal('rhythm_accuracy', 5, 2)->nullable();
            $table->decimal('timing_offset_ms', 8, 2)->nullable();
            $table->decimal('vibrato_score', 5, 2)->nullable();
            $table->decimal('breath_control_score', 5, 2)->nullable();
            $table->decimal('stability_score', 5, 2)->nullable();
            $table->decimal('range_score', 5, 2)->nullable();
            $table->decimal('pronunciation_score', 5, 2)->nullable();
            $table->decimal('energy_score', 5, 2)->nullable();
            $table->decimal('noise_level', 8, 3)->nullable();
            $table->boolean('clipping_detected')->default(false);
            $table->decimal('silence_ratio', 5, 2)->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->json('raw_analysis')->nullable();
            $table->string('features_storage_key', 2048)->nullable();
            $table->timestamps();

            $table->index(['performance_id', 'status']);
        });

        Schema::create('vocal_analysis_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocal_analysis_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('start_ms');
            $table->unsignedInteger('end_ms');
            $table->string('expected_pitch', 12)->nullable();
            $table->string('detected_pitch', 12)->nullable();
            $table->decimal('pitch_error_cents', 8, 2)->nullable();
            $table->decimal('timing_error_ms', 8, 2)->nullable();
            $table->decimal('segment_score', 5, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['vocal_analysis_id', 'start_ms']);
        });

        Schema::create('vocal_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('vocal_range_min', 8)->nullable();
            $table->string('vocal_range_max', 8)->nullable();
            $table->string('comfortable_key', 12)->nullable();
            $table->string('voice_type', 40)->nullable();
            $table->unsignedTinyInteger('current_level')->default(1);
            $table->json('goals')->nullable();
            $table->timestamps();
        });

        Schema::create('vocal_progress_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->string('period_type', 20)->default('weekly');
            $table->decimal('avg_score_overall', 5, 2)->nullable();
            $table->decimal('avg_score_pitch', 5, 2)->nullable();
            $table->decimal('avg_score_rhythm', 5, 2)->nullable();
            $table->decimal('stability_score', 5, 2)->nullable();
            $table->unsignedInteger('performances_count')->default(0);
            $table->unsignedInteger('practice_seconds')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'period_start', 'period_type'], 'vocal_progress_unique_period');
        });

        Schema::create('practice_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('song_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('performance_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->string('goal', 120)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_sessions');
        Schema::dropIfExists('vocal_progress_snapshots');
        Schema::dropIfExists('vocal_profiles');
        Schema::dropIfExists('vocal_analysis_segments');
        Schema::dropIfExists('vocal_analyses');
        Schema::table('performances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_asset_id');
            $table->dropUnique(['uuid']);
            $table->dropIndex('performances_user_created_at_index');
            $table->dropIndex('performances_song_score_index');
            $table->dropIndex('performances_status_created_at_index');
            $table->dropColumn([
                'uuid',
                'status',
                'visibility',
                'published_at',
                'processing_started_at',
                'processing_finished_at',
                'processing_error',
                'analysis_version',
            ]);
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('media_assets');
    }
};
