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
        Schema::create('stocks', function (Blueprint $table) {
    	 $table->id(); // Unique Stock ID
    	 $table->unsignedBigInteger('dispenser_id'); // Reference to the Dispenser
    	 $table->unsignedBigInteger('medicine_id'); // Reference to the Medicine
    	 $table->integer('stock')->default(0); // Current stock of the medicine in this dispenser
    	 $table->timestamps(); // Created at & Updated at

    	 // Foreign key constraints
    	 $table->foreign('dispenser_id')->references('id')->on('dispensers')->onDelete('cascade');
    	 $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
