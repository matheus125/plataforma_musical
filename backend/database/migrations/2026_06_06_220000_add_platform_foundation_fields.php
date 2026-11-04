<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('avatar_url', 2048)->nullable()->after('email');
            $table->string('locale', 12)->default('pt-BR')->after('avatar_url');
            $table->string('country', 2)->nullable()->after('locale');
            $table->string('timezone', 64)->default('America/Sao_Paulo')->after('country');
            $table->string('signup_source', 80)->nullable()->after('timezone');
            $table->timestamp('last_login_at')->nullable()->after('signup_source');
            $table->string('billing_country', 2)->nullable()->after('last_login_at');
            $table->string('customer_provider_id')->nullable()->index()->after('billing_country');
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->string('musical_key', 12)->nullable()->after('language');
            $table->string('source_type', 40)->default('independent')->after('cover_image_url');
            $table->string('license_type', 80)->nullable()->after('source_type');
            $table->string('rights_holder')->nullable()->after('license_type');
            $table->json('available_countries')->nullable()->after('rights_holder');
            $table->text('lyrics')->nullable()->after('available_countries');
            $table->string('lyrics_language', 12)->nullable()->after('lyrics');
            $table->string('vocal_range_min', 8)->nullable()->after('lyrics_language');
            $table->string('vocal_range_max', 8)->nullable()->after('vocal_range_min');
            $table->softDeletes();
            $table->index(['language', 'is_active'], 'songs_language_active_index');
        });

        if (Schema::hasColumn('songs', 'key')) {
            DB::table('songs')->whereNull('musical_key')->update(['musical_key' => DB::raw('"key"')]);
        }
    }

    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropIndex('songs_language_active_index');
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'musical_key',
                'source_type',
                'license_type',
                'rights_holder',
                'available_countries',
                'lyrics',
                'lyrics_language',
                'vocal_range_min',
                'vocal_range_max',
            ]);
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropIndex(['customer_provider_id']);
            $table->dropColumn([
                'username',
                'avatar_url',
                'locale',
                'country',
                'timezone',
                'signup_source',
                'last_login_at',
                'billing_country',
                'customer_provider_id',
            ]);
        });
    }
};
