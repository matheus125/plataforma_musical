<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('song_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('media_url')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('submitted');
            $table->unsignedSmallInteger('score')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['song_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
