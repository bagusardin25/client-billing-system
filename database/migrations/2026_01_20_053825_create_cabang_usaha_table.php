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
        Schema::create('cabang_usaha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_client')->nullable()->constrained('clients')->onDelete('set null');
            $table->string('nama_perusahaan', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('no_telepon', 255)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang_usaha');
    }
};
