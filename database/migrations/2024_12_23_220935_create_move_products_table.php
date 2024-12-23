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
        Schema::disableForeignKeyConstraints();

        Schema::create('move_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('from_warehouse')->constrained('warehouse');
            $table->foreignId('until_warehouse')->constrained('warehouse');
            $table->integer('amount');
            $table->foreignId('user_id');
            $table->foreignId('from_warehouse_id');
            $table->foreignId('until_warehouse_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('move_products');
    }
};
