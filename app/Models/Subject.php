<?php

/**
 * ==============================================================================
 * Tujuan: Model Eloquent untuk data Mata Pelajaran (Subject) dan relasi ke Guru pengampu.
 * Dipakai Oleh: SubjectController, AssignmentController, TeacherController
 * Dependensi: TeachingAssignment, User
 * Daftar Fungsi Utama: teachingAssignments(), teachers()
 * Side Effect: Query DB tabel subjects
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Relasi ke penugasan mengajar mata pelajaran ini.
     */
    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class, 'subject_id');
    }

    /**
     * Relasi ke seluruh guru yang mengampu mata pelajaran ini.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teaching_assignments', 'subject_id', 'teacher_id')
            ->withPivot(['classroom_id', 'academic_year', 'semester'])
            ->distinct();
    }
}
