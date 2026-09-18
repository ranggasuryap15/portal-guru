<?php

/**
 * ==============================================================================
 * Tujuan: Migrasi skema database akademik (Kelas, Mapel, Siswa, Penugasan Mengajar, Presensi, dan Nilai).
 * Dipakai Oleh: Artisan command migrate / Database Seeder
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
        // 1. Data Kelas
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('academic_year', 20)->default('2026/2027');
            $table->timestamps();

            $table->unique(['name', 'academic_year']);
        });

        // 2. Data Mata Pelajaran
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->timestamps();
        });

        // 3. Data Siswa (Terikat ke Kelas)
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->string('nis', 30)->unique();
            $table->string('nisn', 30)->nullable();
            $table->string('name', 100);
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->timestamps();

            $table->index('classroom_id');
        });

        // 4. Penugasan Guru - Mapel - Kelas
        Schema::create('teaching_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('academic_year', 20)->default('2026/2027');
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->timestamps();

            $table->unique(['teacher_id', 'classroom_id', 'subject_id', 'academic_year', 'semester'], 'ta_unique_idx');
            $table->index(['classroom_id', 'subject_id']);
            $table->index('teacher_id');
        });

        // 5. Presensi Harian Siswa per Mapel
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_assignment_id')->constrained('teaching_assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->unique(['teaching_assignment_id', 'student_id', 'date'], 'attendance_unique_idx');
            $table->index(['teaching_assignment_id', 'date']);
            $table->index('student_id');
        });

        // 6. Penilaian Semester (4 Ulangan Harian + Ujian Akhir)
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_assignment_id')->constrained('teaching_assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('uh1', 5, 2)->nullable();
            $table->decimal('uh2', 5, 2)->nullable();
            $table->decimal('uh3', 5, 2)->nullable();
            $table->decimal('uh4', 5, 2)->nullable();
            $table->decimal('exam_score', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['teaching_assignment_id', 'student_id'], 'grade_unique_idx');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('teaching_assignments');
        Schema::dropIfExists('students');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classrooms');
    }
};
