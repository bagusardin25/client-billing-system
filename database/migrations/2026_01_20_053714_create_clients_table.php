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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nama_client', 255)->nullable();
            $table->string('perusahaan', 255)->nullable();
            $table->integer('status_pembayaran')->default(0);
            $table->string('bulan', 255)->nullable();
            $table->string('no_telepon', 50)->nullable();
            $table->bigInteger('tagihan')->default(0);
            $table->string('kode_client', 255)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('jabatan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
