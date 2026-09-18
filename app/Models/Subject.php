<?php

/**
 * ==============================================================================
 * Tujuan: Model Eloquent untuk data Mata Pelajaran (Subject).
 * Dipakai Oleh: SubjectController, AssignmentController, TeacherController
 * Dependensi: TeachingAssignment
 * Daftar Fungsi Utama: teachingAssignments()
 * Side Effect: Query DB tabel subjects
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
