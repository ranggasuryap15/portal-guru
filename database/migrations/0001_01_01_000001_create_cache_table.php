<?php

/**
 * ==============================================================================
 * Tujuan: Migrasi database untuk tabel cache dan cache_locks.
 * Dipakai Oleh: Artisan command migrate / Cache Manager
 * Dependensi: Illuminate\Database\Migrations\Migration, Schema, Blueprint
 * Daftar Fungsi: up(), down()
 * Side Effect: DDL CREATE TABLE & DROP TABLE pada database MySQL
 * ==============================================================================
 */

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
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->bigInteger('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->bigInteger('expiration')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
