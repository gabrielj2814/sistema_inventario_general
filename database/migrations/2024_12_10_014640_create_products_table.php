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
            $table->string('name', 140);
            $table->string('description', 255)->nullable();
            $table->string('barcode', 255)->unique();
            $table->integer('stock');
            $table->decimal('price_unit', 8, 2);
            $table->enum('unit_of_measurement', ["mg","g","kg","t","mm","cm","m","km","ud","dz","pkg","box"]);
            $table->foreignId('category_id');
            $table->timestamps();
            $table->softDeletes();
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