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
        Schema::create('reseller', function (Blueprint $table) {
            $table->id();
            $table->string('nama', length: 50);
            $table->string('alamat', length: 100);
            $table->bigInteger('nohp');
            $table->integer('tunggakan');
            $table->string('area', length: 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller');
    }
};
