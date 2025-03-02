<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('image_conversions', function (Blueprint $table) {
            $table->integer('quality')->default(80)->after('converted_size');
        });
    }

    public function down()
    {
        Schema::table('image_conversions', function (Blueprint $table) {
            $table->dropColumn('quality');
        });
    }
}; 