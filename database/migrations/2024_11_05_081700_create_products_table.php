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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');

            $table->string('title');
            $table->text('tags')->nullable();
            $table->string('slug')->unique();
            $table->longText('description');
            $table->enum('type', ['physical', 'digital'])->default('physical');

            $table->float('quantity')->default(0);
            $table->integer('unit')->default(1);
            $table->float('price')->default(0);
            $table->float('tax')->default(0);
            $table->float('shipping')->default(0);

            $table->float('discount')->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->datetime('discount_start')->nullable();
            $table->datetime('discount_end')->nullable();
            
            $table->string('thumbnail');
            $table->string('images');

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('upcomming')->default(false);

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();

            $table->string('external_link')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
