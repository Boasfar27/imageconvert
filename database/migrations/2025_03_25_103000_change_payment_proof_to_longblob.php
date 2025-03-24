<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using DB statement as Laravel's Schema doesn't directly support LONGBLOB
        DB::statement('ALTER TABLE donations MODIFY payment_proof LONGBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting back to string in case of rollback
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->change();
        });
    }
}; 