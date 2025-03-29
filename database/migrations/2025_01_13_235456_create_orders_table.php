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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // Patient
        $table->unsignedBigInteger('prescription_id');
        $table->unsignedBigInteger('medicine_id');
        $table->integer('quantity');
        $table->enum('status', ['requested', 'dispensed'])->default('requested');
        $table->timestamps();

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
        $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
   {
    Schema::dropIfExists('orders');
   }
};
