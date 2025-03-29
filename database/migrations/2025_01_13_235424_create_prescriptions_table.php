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
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // Patient
        $table->unsignedBigInteger('doctor_id'); // Doctor
        $table->string('qr_code')->unique();
        $table->enum('status', ['pending', 'approved', 'dispensed'])->default('pending');
        $table->timestamps();

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
  {
    Schema::dropIfExists('prescriptions');
  }
};
