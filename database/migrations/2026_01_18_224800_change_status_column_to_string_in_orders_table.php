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
        Schema::table('orders', function (Blueprint $table) {
            // Change enum to string to support new statuses like 'waiting_verification', 'declined', etc.
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert back to enum (note: this might lose data if statuses don't match)
            // It's safer to leave as string in down() or define the exact enum again.
            // For now, let's keep it simple.
        });
    }
};
