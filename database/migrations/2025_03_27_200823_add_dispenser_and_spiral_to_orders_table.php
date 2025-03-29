<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('dispenser_id')->nullable()->constrained()->after('medicine_id');
            $table->unsignedInteger('spiral')->nullable()->after('dispenser_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['dispenser_id']);
            $table->dropColumn(['dispenser_id', 'spiral']);
        });
    }
};
