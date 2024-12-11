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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->date('date_movement');
            $table->enum('type', ["estrada","salida","ajuste"]);
            $table->integer('amount');
            $table->string('note', 255);
            $table->foreignId('user_id');
            $table->foreignId('product_supplier_id');
            $table->foreignId('warehouse_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
