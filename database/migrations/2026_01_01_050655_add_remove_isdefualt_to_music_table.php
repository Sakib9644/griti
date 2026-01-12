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
        Schema::table('music', function (Blueprint $table) {
            $table->boolean('is_default')->default(false);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('music_id')
                ->nullable()
                ->constrained('music')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['music_id']);
            $table->dropColumn('music_id');
        });

        Schema::table('music', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
