<?php

/**
 * ==============================================================================
 * Tujuan: Seeder utama database untuk memanggil AcademicSeeder.
 * Dipakai Oleh: Artisan command db:seed / migrate --seed
 * Dependensi: AcademicSeeder
 * Daftar Fungsi: run()
 * Side Effect: Menjalankan eksekusi seeder akademik
 * ==============================================================================
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AcademicSeeder::class,
        ]);
    }
}
