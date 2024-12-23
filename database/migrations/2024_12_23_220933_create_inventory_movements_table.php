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

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->date('date_movement');
            $table->enum('type', ["entrada","salida","ajuste"]);
            $table->integer('amount');
            $table->string('note', 255);
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('product_supplier_id')->nullable()->constrained();
            $table->foreignId('user_id');
            $table->foreignId('warehouse_id');
            $table->foreignId('product_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
