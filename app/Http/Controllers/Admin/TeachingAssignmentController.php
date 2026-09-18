<?php

/**
 * ==============================================================================
 * Tujuan: Controller Penugasan Mengajar (Assign Guru ke Multi-Mapel dan Multi-Kelas) oleh Administrator.
 * Dipakai Oleh: routes/web.php (Route /admin/assignments/*)
 * Dependensi: App\Models\TeachingAssignment, User, Classroom, Subject
 * Daftar Fungsi: index(), create(), store(), destroy()
 * Side Effect: Insert & delete penugasan di tabel teaching_assignments
 * ==============================================================================
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeachingAssignmentController extends Controller
{
    /**
     * Tampilkan daftar penugasan mengajar aktif.
     */
    public function index(): View
    {
        $assignments = TeachingAssignment::with(['teacher', 'classroom', 'subject'])
            ->withCount(['attendances', 'grades'])
            ->latest()
            ->paginate(15);

        return view('admin.assignments.index', compact('assignments'));
    }

    /**
     * Form tambah penugasan guru ke mapel & kelas.
     */
    public function create(): View
    {
        $teachers = User::where('role', 'guru')->orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.assignments.create', compact('teachers', 'classrooms', 'subjects'));
    }

    /**
     * Simpan penugasan guru ke mapel dan kelas (mendukung multi-mapel dan multi-kelas sekaligus).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:users,id'],
            'subject_ids' => ['required_without:subject_id', 'array', 'min:1'],
            'subject_ids.*' => ['exists:subjects,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'classroom_ids' => ['required', 'array', 'min:1'],
            'classroom_ids.*' => ['required', 'exists:classrooms,id'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:ganjil,genap'],
        ], [
            'teacher_id.required' => 'Pilih guru pengampu.',
            'subject_ids.required_without' => 'Pilih minimal satu mata pelajaran.',
            'classroom_ids.required' => 'Pilih minimal satu kelas.',
            'semester.required' => 'Pilih semester.',
        ]);

        $subjectIds = $request->input('subject_ids', []);
        if (empty($subjectIds) && $request->filled('subject_id')) {
            $subjectIds = [$request->input('subject_id')];
        }

        $createdCount = 0;

        foreach ($subjectIds as $subjectId) {
            foreach ($validated['classroom_ids'] as $classroomId) {
                $assignment = TeachingAssignment::firstOrCreate([
                    'teacher_id' => $validated['teacher_id'],
                    'classroom_id' => $classroomId,
                    'subject_id' => $subjectId,
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                ]);

                if ($assignment->wasRecentlyCreated) {
                    $createdCount++;
                }
            }
        }

        return redirect()->route('admin.assignments.index')
            ->with('success', "Berhasil mendaftarkan penugasan guru ({$createdCount} jadwal baru dibuat).");
    }

    /**
     * Hapus penugasan mengajar.
     */
    public function destroy(TeachingAssignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Penugasan mengajar berhasil dihapus.');
    }
}
