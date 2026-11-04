<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('song_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 12);
            $table->string('title', 160)->nullable();
            $table->string('artist_display', 160)->nullable();
            $table->string('genre_display', 80)->nullable();
            $table->text('lyrics')->nullable();
            $table->timestamps();

            $table->unique(['song_id', 'locale']);
        });

        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id', 120)->nullable()->index();
            $table->string('event_name', 120);
            $table->string('entity_type', 80)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_hash', 128)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('locale', 12)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['event_name', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
        });

        Schema::create('ranking_periods', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->string('scope', 40)->default('global');
            $table->string('country', 2)->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status', 30)->default('scheduled');
            $table->timestamps();

            $table->index(['type', 'scope', 'starts_at']);
        });

        Schema::create('ranking_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('song_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('score', 8, 2)->default(0);
            $table->unsignedInteger('rank')->nullable();
            $table->unsignedInteger('performances_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['ranking_period_id', 'user_id', 'song_id'], 'ranking_entries_unique_scope');
            $table->index(['ranking_period_id', 'rank']);
        });

        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type', 40)->default('song');
            $table->json('rules')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('locale', 12)->default('pt-BR');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status', 30)->default('draft');
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'starts_at', 'ends_at']);
        });

        Schema::create('challenge_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('song_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['challenge_id', 'song_id']);
        });

        Schema::create('challenge_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status', 30)->default('joined');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['challenge_id', 'user_id']);
        });

        Schema::create('challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 8, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['challenge_id', 'performance_id']);
            $table->index(['challenge_id', 'score']);
        });

        Schema::create('challenge_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->string('type', 40);
            $table->string('name');
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('currency', 3)->default('BRL');
            $table->unsignedInteger('price_cents')->default(0);
            $table->string('billing_cycle', 30)->default('monthly');
            $table->json('limits')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider', 40)->nullable();
            $table->string('provider_subscription_id')->nullable()->index();
            $table->string('status', 40)->default('trialing');
            $table->timestamp('current_period_starts_at')->nullable();
            $table->timestamp('current_period_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider', 40);
            $table->string('provider_payment_id')->nullable()->index();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 3)->default('BRL');
            $table->string('status', 40);
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->integer('value')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'key']);
        });

        Schema::create('usage_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->unsignedInteger('used')->default(0);
            $table->unsignedInteger('limit')->default(0);
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();

            $table->unique(['user_id', 'key', 'period_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_limits');
        Schema::dropIfExists('entitlements');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('challenge_rewards');
        Schema::dropIfExists('challenge_submissions');
        Schema::dropIfExists('challenge_participants');
        Schema::dropIfExists('challenge_songs');
        Schema::dropIfExists('challenges');
        Schema::dropIfExists('ranking_entries');
        Schema::dropIfExists('ranking_periods');
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('song_translations');
    }
};
