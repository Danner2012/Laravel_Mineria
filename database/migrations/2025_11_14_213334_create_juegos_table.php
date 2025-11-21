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
        Schema::create('juegos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('rating', 5, 2)->nullable();
            $table->decimal('price_original', 10, 2);
            $table->decimal('price_current', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->integer('days_until_sale')->nullable();
            $table->string('genre')->nullable();
            $table->integer('reviews_count')->nullable();
            $table->enum('player_trend', ['growing', 'falling', 'stable'])->nullable();
            $table->boolean('will_be_discounted')->default(false);
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juegos');
    }
};
