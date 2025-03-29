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
        Schema::create('dispensers', function (Blueprint $table) {
    	 $table->id(); // Unique ID
    	 $table->string('name'); // Name of the dispenser
    	 $table->string('location'); // Physical location of the dispenser
    	 $table->string('model'); // Model of the dispenser
    	 $table->integer('capacity'); // Total capacity (e.g., total strips)
    	 $table->integer('rows'); // Number of rows in the dispenser
    	 $table->integer('columns'); // Number of columns in the dispenser
    	 $table->timestamps(); // Created at & Updated at
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispensers');
    }
};
