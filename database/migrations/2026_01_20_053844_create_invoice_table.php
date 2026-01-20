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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoive', 255)->nullable();
            $table->foreignId('id_client')->constrained('clients')->onDelete('cascade');
            $table->string('nama_client', 255)->nullable();
            $table->string('bulan', 10)->nullable();
            $table->string('tahun', 10)->nullable();
            $table->bigInteger('tagihan')->nullable();
            $table->string('tanggal_pembayaran', 255)->nullable();
            $table->integer('status_pembayaran')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
