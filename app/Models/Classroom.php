<?php

/**
 * ==============================================================================
 * Tujuan: Model Eloquent untuk data Kelas (Classroom).
 * Dipakai Oleh: ClassroomController, StudentController, AssignmentController, TeacherController
 * Dependensi: Student, TeachingAssignment
 * Daftar Fungsi Utama: students(), teachingAssignments()
 * Side Effect: Query DB tabel classrooms
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year',
    ];

    /**
     * Relasi ke siswa yang terdaftar di kelas ini.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }

    /**
     * Relasi ke penugasan mapel & guru di kelas ini.
     */
    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class, 'classroom_id');
    }
}
