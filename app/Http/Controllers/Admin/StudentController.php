<?php

/**
 * ==============================================================================
 * Tujuan: Controller CRUD Data Siswa dan Penempatan Kelas oleh Administrator.
 * Dipakai Oleh: routes/web.php (Route /admin/students/*)
 * Dependensi: App\Models\Student, Classroom
 * Daftar Fungsi: index(), create(), store(), edit(), update(), destroy()
 * Side Effect: Insert, update, delete siswa di tabel students
 * ==============================================================================
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Tampilkan daftar siswa (dapat difilter berdasarkan kelas).
     */
    public function index(Request $request): View
    {
        $classroomId = $request->query('classroom_id');
        $search = $request->query('search');

        $classrooms = Classroom::orderBy('name')->get();

        $students = Student::with('classroom')
            ->when($classroomId, fn ($query) => $query->where('classroom_id', $classroomId))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'classrooms', 'classroomId', 'search'));
    }

    /**
     * Form tambah siswa baru.
     */
    public function create(): View
    {
        $classrooms = Classroom::orderBy('name')->get();

        return view('admin.students.create', compact('classrooms'));
    }

    /**
     * Simpan data siswa baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'nis' => ['required', 'string', 'max:30', 'unique:students,nis'],
            'nisn' => ['nullable', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:L,P'],
        ], [
            'classroom_id.required' => 'Pilih kelas siswa.',
            'nis.required' => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique' => 'NIS ini sudah terdaftar untuk siswa lain.',
            'name.required' => 'Nama lengkap siswa wajib diisi.',
            'gender.required' => 'Pilih jenis kelamin.',
        ]);

        Student::create($validated);

        return redirect()->route('admin.students.index', ['classroom_id' => $validated['classroom_id']])
            ->with('success', 'Data siswa berhasil didaftarkan ke kelas.');
    }

    /**
     * Form edit siswa.
     */
    public function edit(Student $student): View
    {
        $classrooms = Classroom::orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'classrooms'));
    }

    /**
     * Perbarui data siswa.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($student->id)],
            'nisn' => ['nullable', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:L,P'],
        ], [
            'classroom_id.required' => 'Pilih kelas siswa.',
            'nis.required' => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique' => 'NIS ini sudah terdaftar untuk siswa lain.',
            'name.required' => 'Nama lengkap siswa wajib diisi.',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index', ['classroom_id' => $student->classroom_id])
            ->with('success', 'Data siswa ' . $student->name . ' berhasil diperbarui.');
    }

    /**
     * Hapus siswa.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $name = $student->name;
        $classroomId = $student->classroom_id;
        $student->delete();

        return redirect()->route('admin.students.index', ['classroom_id' => $classroomId])
            ->with('success', 'Data siswa ' . $name . ' berhasil dihapus.');
    }
}
