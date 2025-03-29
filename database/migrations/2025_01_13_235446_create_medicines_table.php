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
    Schema::create('medicines', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('batch_number');
        $table->string('manufacturer');
        $table->date('expiry_date');
        $table->integer('stock_quantity');
        $table->string('dosage');
        $table->timestamps();
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
   {
    Schema::dropIfExists('medicines');
   }
};
