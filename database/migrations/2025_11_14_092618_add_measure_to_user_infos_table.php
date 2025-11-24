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
        Schema::table('user_infos', function (Blueprint $table) {
            $table->string('height_in')->nullable()->after('id');       // user's height
            $table->string('weight_in')->nullable()->after('height_in');            // current weight
            $table->string('target_weight_in')->nullable()->after('weight_in');     // target weight
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn(['height_in', 'weight_in', 'target_weight_in']);
        });
    }
};
