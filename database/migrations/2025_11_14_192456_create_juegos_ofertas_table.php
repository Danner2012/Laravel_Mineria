<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('juegos_ofertas', function (Blueprint $table) {
        $table->id();
        $table->string('game')->nullable();
        $table->float('discount');
        $table->float('price_current');
        $table->float('rating');
        $table->float('price_original');
        $table->boolean('will_be_discounted');
        $table->timestamp('created_at')->useCurrent();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juegos_ofertas');
    }
};
