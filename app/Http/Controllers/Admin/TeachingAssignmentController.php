<?php

/**
 * ==============================================================================
 * Tujuan: Controller Penugasan Mengajar (Assign Guru ke Mapel dan Kelas) oleh Administrator.
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
     * Simpan penugasan guru ke mapel dan kelas (bisa multi-kelas sekaligus).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'classroom_ids' => ['required', 'array', 'min:1'],
            'classroom_ids.*' => ['required', 'exists:classrooms,id'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:ganjil,genap'],
        ], [
            'teacher_id.required' => 'Pilih guru pengampu.',
            'subject_id.required' => 'Pilih mata pelajaran.',
            'classroom_ids.required' => 'Pilih minimal satu kelas.',
            'semester.required' => 'Pilih semester.',
        ]);

        $createdCount = 0;

        foreach ($validated['classroom_ids'] as $classroomId) {
            $exists = TeachingAssignment::where([
                'teacher_id' => $validated['teacher_id'],
                'classroom_id' => $classroomId,
                'subject_id' => $validated['subject_id'],
                'academic_year' => $validated['academic_year'],
                'semester' => $validated['semester'],
            ])->exists();

            if (! $exists) {
                TeachingAssignment::create([
                    'teacher_id' => $validated['teacher_id'],
                    'classroom_id' => $classroomId,
                    'subject_id' => $validated['subject_id'],
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                ]);
                $createdCount++;
            }
        }

        return redirect()->route('admin.assignments.index')
            ->with('success', "Berhasil mendaftarkan guru ke {$createdCount} kelas penugasan.");
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
