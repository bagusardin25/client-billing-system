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
        // Change tanggal from string to date in pemasukan table
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->date('tanggal_new')->nullable()->after('tanggal');
        });

        // Copy data (convert string to date)
        \DB::statement("UPDATE pemasukan SET tanggal_new = STR_TO_DATE(tanggal, '%Y-%m-%d') WHERE tanggal IS NOT NULL AND tanggal != ''");

        Schema::table('pemasukan', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });

        Schema::table('pemasukan', function (Blueprint $table) {
            $table->renameColumn('tanggal_new', 'tanggal');
        });

        // Change tanggal from string to date in pengeluaran table
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->date('tanggal_new')->nullable()->after('tanggal');
        });

        // Copy data (convert string to date)
        \DB::statement("UPDATE pengeluaran SET tanggal_new = STR_TO_DATE(tanggal, '%Y-%m-%d') WHERE tanggal IS NOT NULL AND tanggal != ''");

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->renameColumn('tanggal_new', 'tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse pemasukan table
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->string('tanggal_old', 50)->nullable()->after('tanggal');
        });

        \DB::statement("UPDATE pemasukan SET tanggal_old = DATE_FORMAT(tanggal, '%Y-%m-%d') WHERE tanggal IS NOT NULL");

        Schema::table('pemasukan', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });

        Schema::table('pemasukan', function (Blueprint $table) {
            $table->renameColumn('tanggal_old', 'tanggal');
        });

        // Reverse pengeluaran table
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->string('tanggal_old', 50)->nullable()->after('tanggal');
        });

        \DB::statement("UPDATE pengeluaran SET tanggal_old = DATE_FORMAT(tanggal, '%Y-%m-%d') WHERE tanggal IS NOT NULL");

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->renameColumn('tanggal_old', 'tanggal');
        });
    }
};
