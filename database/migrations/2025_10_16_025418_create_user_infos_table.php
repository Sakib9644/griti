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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('age')->nullable();
            $table->string('bmi')->nullable();
            $table->string('body_part_focus')->nullable();
            $table->string('body_satisfaction')->nullable();
            $table->string('celebration_plan')->nullable();
            $table->string('current_body_type')->nullable();
            $table->decimal('current_weight', 10, 2)->nullable();
            $table->string('dream_body')->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('target_weight', 10, 2)->nullable();
            $table->string('trying_duration')->nullable();
            $table->string('urgent_improvement')->nullable();
            $table->decimal('price', 10, 2); // store payment amount
            $table->timestamps();

            // Optional: add foreign key constraint if you have users table
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
