<?php

/**
 * ==============================================================================
 * Tujuan: Controller CRUD Data Kelas (Classroom) oleh Administrator.
 * Dipakai Oleh: routes/web.php (Route /admin/classrooms/*)
 * Dependensi: App\Models\Classroom
 * Daftar Fungsi: index(), create(), store(), edit(), update(), destroy()
 * Side Effect: Insert, update, delete data di tabel classrooms
 * ==============================================================================
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    /**
     * Tampilkan daftar kelas.
     */
    public function index(): View
    {
        $classrooms = Classroom::withCount(['students', 'teachingAssignments'])
            ->latest()
            ->paginate(10);

        return view('admin.classrooms.index', compact('classrooms'));
    }

    /**
     * Form tambah kelas baru.
     */
    public function create(): View
    {
        return view('admin.classrooms.create');
    }

    /**
     * Simpan data kelas baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('classrooms')->where(fn ($query) => $query->where('academic_year', $request->input('academic_year', '2026/2027'))),
            ],
            'academic_year' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas sudah ada untuk tahun ajaran yang dipilih.',
            'academic_year.required' => 'Tahun ajaran wajib diisi.',
        ]);

        Classroom::create($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    /**
     * Form edit kelas.
     */
    public function edit(Classroom $classroom): View
    {
        return view('admin.classrooms.edit', compact('classroom'));
    }

    /**
     * Perbarui data kelas.
     */
    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('classrooms')
                    ->where(fn ($query) => $query->where('academic_year', $request->input('academic_year', $classroom->academic_year)))
                    ->ignore($classroom->id),
            ],
            'academic_year' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas sudah ada untuk tahun ajaran yang dipilih.',
        ]);

        $classroom->update($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Data kelas ' . $classroom->name . ' berhasil diperbarui.');
    }

    /**
     * Hapus kelas.
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        $name = $classroom->name;
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas ' . $name . ' berhasil dihapus.');
    }
}
