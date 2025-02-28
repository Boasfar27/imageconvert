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
        Schema::create('image_conversions', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('original_path');
            $table->string('converted_path');
            $table->string('original_format');
            $table->string('converted_format');
            $table->integer('original_size');
            $table->integer('converted_size');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_conversions');
    }
};
