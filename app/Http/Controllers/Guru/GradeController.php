<?php

/**
 * ==============================================================================
 * Tujuan: Controller Pengelolaan Nilai Siswa (4 UH + Ujian Akhir Semester) oleh Guru.
 * Dipakai Oleh: routes/web.php (Route /guru/grades/*)
 * Dependensi: Illuminate\Support\Facades\Auth, App\Models\TeachingAssignment, Grade, Student
 * Daftar Fungsi: index(), edit(), update(), recap()
 * Side Effect: Insert & update data nilai di tabel grades dengan formula 60% UH + 40% Ujian
 * ==============================================================================
 */

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\TeachingAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GradeController extends Controller
{
    /**
     * Tampilkan daftar mapel & kelas untuk pengelolaan nilai.
     */
    public function index(): View
    {
        $assignments = TeachingAssignment::where('teacher_id', Auth::id())
            ->with(['classroom.students', 'subject', 'grades'])
            ->latest()
            ->get();

        return view('guru.grades.index', compact('assignments'));
    }

    /**
     * Tampilkan tabel input nilai 4 Ulangan Harian dan Ujian untuk seluruh siswa di kelas.
     */
    public function edit(TeachingAssignment $assignment): View
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403, 'Anda bukan guru pengampu kelas/mapel ini.');

        // Ambil semua siswa di kelas ini beserta nilai yang sudah tersimpan
        $students = Student::where('classroom_id', $assignment->classroom_id)
            ->with(['grades' => fn ($q) => $q->where('teaching_assignment_id', $assignment->id)])
            ->orderBy('name')
            ->get();

        return view('guru.grades.edit', compact('assignment', 'students'));
    }

    /**
     * Simpan / Perbarui nilai siswa (UH 1-4 dan Ujian) dengan perhitungan otomatis nilai akhir.
     */
    public function update(Request $request, TeachingAssignment $assignment): RedirectResponse
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $validated = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*.uh1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.uh2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.uh3' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.uh4' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.exam_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ], [
            'grades.*.*.numeric' => 'Nilai harus berupa angka.',
            'grades.*.*.min' => 'Nilai minimal adalah 0.',
            'grades.*.*.max' => 'Nilai maksimal adalah 100.',
        ]);

        DB::transaction(function () use ($assignment, $validated) {
            foreach ($validated['grades'] as $studentId => $scores) {
                // Parsing nilai null jika string kosong dikirim
                $uh1 = isset($scores['uh1']) && $scores['uh1'] !== '' ? (float) $scores['uh1'] : null;
                $uh2 = isset($scores['uh2']) && $scores['uh2'] !== '' ? (float) $scores['uh2'] : null;
                $uh3 = isset($scores['uh3']) && $scores['uh3'] !== '' ? (float) $scores['uh3'] : null;
                $uh4 = isset($scores['uh4']) && $scores['uh4'] !== '' ? (float) $scores['uh4'] : null;
                $exam = isset($scores['exam_score']) && $scores['exam_score'] !== '' ? (float) $scores['exam_score'] : null;

                $grade = Grade::firstOrNew([
                    'teaching_assignment_id' => $assignment->id,
                    'student_id' => $studentId,
                ]);

                $grade->uh1 = $uh1;
                $grade->uh2 = $uh2;
                $grade->uh3 = $uh3;
                $grade->uh4 = $uh4;
                $grade->exam_score = $exam;
                $grade->final_score = $grade->calculateFinalScore();
                $grade->save();
            }
        });

        return redirect()->route('guru.grades.edit', $assignment->id)
            ->with('success', 'Seluruh nilai siswa berhasil disimpan dan Nilai Akhir (60% UH + 40% Ujian) telah dihitung otomatis.');
    }

    /**
     * Tampilkan rekapitulasi leger nilai semester untuk dicetak atau ditinjau.
     */
    public function recap(TeachingAssignment $assignment): View
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $students = Student::where('classroom_id', $assignment->classroom_id)
            ->with(['grades' => fn ($q) => $q->where('teaching_assignment_id', $assignment->id)])
            ->orderBy('name')
            ->get();

        $gradesCollection = $students->pluck('grades')->flatten()->filter(fn ($g) => !is_null($g->final_score));

        $stats = [
            'count' => $gradesCollection->count(),
            'avg' => $gradesCollection->count() ? round($gradesCollection->avg('final_score'), 2) : 0,
            'max' => $gradesCollection->count() ? $gradesCollection->max('final_score') : 0,
            'min' => $gradesCollection->count() ? $gradesCollection->min('final_score') : 0,
        ];

        return view('guru.grades.recap', compact('assignment', 'students', 'stats'));
    }
}
