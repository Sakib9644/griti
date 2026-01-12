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
    Schema::table('videos', function (Blueprint $table) {
        // Drop the foreign key first

        // Make column nullable
        $table->foreignId('theme_id')
            ->nullable()
            ->change();

        // Re-add the foreign key
        $table->foreign('theme_id')
            ->references('id')
            ->on('themes')
            ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('videos', function (Blueprint $table) {
        $table->dropForeign(['theme_id']);

        $table->foreignId('theme_id')
            ->constrained('themes')
            ->onDelete('themes')
            ->change();
    });
}

};
