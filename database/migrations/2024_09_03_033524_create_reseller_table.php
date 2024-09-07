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
            $table->string('bandwith', length:10)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); 
            // OLT details
            $table->string('olt_sn', 50)->nullable();
            $table->string('olt_type_modem', 50)->nullable();
            $table->string('olt_lokasi_pop', 100)->nullable();
            $table->string('olt_secret', 50)->nullable();
            $table->string('olt_ip_address', 45)->nullable();
            $table->enum('olt_statik', ['PRIVATE', 'PUBLIC'])->nullable();
            
            // Switch details
            $table->string('switch_type_sfp', 50)->nullable();
            $table->string('switch_sn_sfp', 50)->nullable();
            $table->string('switch_lokasi_pop', 100)->nullable();
            $table->integer('switch_port_number')->nullable();
            $table->enum('switch_statik', ['PRIVATE', 'PUBLIC'])->nullable();
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
