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
        Schema::create('qrcodes', function (Blueprint $table) {
    	 $table->id(); // Unique QR Code ID
    	 $table->unsignedBigInteger('order_id'); // Reference to the Order
    	 $table->boolean('used')->default(false); // Whether the QR code has been used
    	 $table->timestamps(); // Created at & Updated at

    	 // Foreign key constraint
    	 $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
	 });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('q_r_codes');
    }
};
