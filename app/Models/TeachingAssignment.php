<?php

/**
 * ==============================================================================
 * Tujuan: Model Penugasan Mengajar (TeachingAssignment) menghubungkan Guru, Mapel, dan Kelas.
 * Dipakai Oleh: AssignmentController, Guru DashboardController, AttendanceController, GradeController
 * Dependensi: User, Classroom, Subject, Attendance, Grade
 * Daftar Fungsi Utama: teacher(), classroom(), subject(), attendances(), grades()
 * Side Effect: Query DB tabel teaching_assignments
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeachingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'subject_id',
        'academic_year',
        'semester',
    ];

    /**
     * Relasi ke Guru yang mengajar.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relasi ke Kelas tempat penugasan mengajar.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Relasi ke Mata Pelajaran yang diajarkan.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relasi ke presensi siswa untuk penugasan ini.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'teaching_assignment_id');
    }

    /**
     * Relasi ke nilai siswa untuk penugasan ini.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'teaching_assignment_id');
    }
}
