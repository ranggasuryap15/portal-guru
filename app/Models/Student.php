<?php

/**
 * ==============================================================================
 * Tujuan: Model Eloquent untuk data Siswa (Student).
 * Dipakai Oleh: StudentController, AttendanceController, GradeController
 * Dependensi: Classroom, Attendance, Grade
 * Daftar Fungsi Utama: classroom(), attendances(), grades()
 * Side Effect: Query DB tabel students
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'nis',
        'nisn',
        'name',
        'gender',
    ];

    /**
     * Relasi ke kelas tempat siswa terdaftar.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Relasi ke data presensi siswa.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * Relasi ke nilai siswa.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'student_id');
    }
}
