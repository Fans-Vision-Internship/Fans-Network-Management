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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained('reseller')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('bandwith', length:10);
            $table->string('keterangan')->nullable();
            $table->integer('spare')->nullable();
            $table->integer('tunggakan');
            $table->integer('total_tagihan');
            $table->integer('total_pembayaran');
            $table->integer('harga_bw');
            $table->integer('biaya_aktivasi')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
