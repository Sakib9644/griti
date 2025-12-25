<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // foreign key to users
            $table->string('meal');
            $table->text('description')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('steps')->nullable();
            $table->integer('time_min')->default(0);
            $table->integer('calories')->default(0);
            $table->float('protein_g', 8, 2)->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();

            // Foreign key constraint (optional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
