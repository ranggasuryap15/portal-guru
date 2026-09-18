<?php

/**
 * ==============================================================================
 * Tujuan: Model Eloquent untuk data Presensi Siswa per Mapel dan Tanggal.
 * Dipakai Oleh: AttendanceController
 * Dependensi: TeachingAssignment, Student
 * Daftar Fungsi Utama: teachingAssignment(), student()
 * Side Effect: Query & insert/update DB tabel attendances
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_assignment_id',
        'student_id',
        'date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * Relasi ke penugasan mengajar terkait.
     */
    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class, 'teaching_assignment_id');
    }

    /**
     * Relasi ke siswa yang diabsen.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
