<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('music', function (Blueprint $table) {
            // First drop foreign key
            $table->dropForeign(['workout_videos_id']);

            // Then drop column
            $table->dropColumn('workout_videos_id');
        });
    }

    public function down(): void
    {

    }
};
