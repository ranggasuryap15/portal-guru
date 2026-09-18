<?php

/**
 * ==============================================================================
 * Tujuan: Model Eloquent Penilaian Siswa (4 Ulangan Harian + Ujian Akhir Semester).
 * Dipakai Oleh: GradeController
 * Dependensi: TeachingAssignment, Student
 * Daftar Fungsi Utama: calculateFinalScore(), teachingAssignment(), student()
 * Side Effect: Query DB tabel grades, kalkulasi otomatis nilai akhir semester (60% UH + 40% Ujian)
 * ==============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_assignment_id',
        'student_id',
        'uh1',
        'uh2',
        'uh3',
        'uh4',
        'exam_score',
        'final_score',
    ];

    protected function casts(): array
    {
        return [
            'uh1' => 'float',
            'uh2' => 'float',
            'uh3' => 'float',
            'uh4' => 'float',
            'exam_score' => 'float',
            'final_score' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Grade $grade) {
            $grade->final_score = $grade->calculateFinalScore();
        });
    }

    /**
     * Hitung rata-rata 4 ulangan harian.
     */
    public function getAverageUhAttribute(): ?float
    {
        $uhScores = array_filter([$this->uh1, $this->uh2, $this->uh3, $this->uh4], fn($v) => !is_null($v));
        if (empty($uhScores)) {
            return null;
        }
        return round(array_sum($uhScores) / count($uhScores), 2);
    }

    /**
     * Hitung Nilai Akhir Semester: 60% Ulangan Harian + 40% Ujian.
     */
    public function calculateFinalScore(): ?float
    {
        $uhScores = array_filter([$this->uh1, $this->uh2, $this->uh3, $this->uh4], fn($v) => !is_null($v));
        
        if (empty($uhScores) && is_null($this->exam_score)) {
            return null;
        }

        $avgUh = !empty($uhScores) ? (array_sum($uhScores) / count($uhScores)) : 0;
        $exam = $this->exam_score ?? 0;

        return round(($avgUh * 0.60) + ($exam * 0.40), 2);
    }

    /**
     * Relasi ke penugasan mengajar terkait.
     */
    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class, 'teaching_assignment_id');
    }

    /**
     * Relasi ke siswa yang dinilai.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
