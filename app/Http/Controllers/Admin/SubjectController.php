<?php

/**
 * ==============================================================================
 * Tujuan: Controller CRUD Data Mata Pelajaran (Subject) oleh Administrator.
 * Dipakai Oleh: routes/web.php (Route /admin/subjects/*)
 * Dependensi: App\Models\Subject
 * Daftar Fungsi: index(), create(), store(), edit(), update(), destroy()
 * Side Effect: Insert, update, delete data di tabel subjects
 * ==============================================================================
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends Controller
{
    /**
     * Tampilkan daftar mata pelajaran.
     */
    public function index(): View
    {
        $subjects = Subject::withCount('teachingAssignments')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Form tambah mapel baru.
     */
    public function create(): View
    {
        return view('admin.subjects.create');
    }

    /**
     * Simpan data mapel baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:100'],
        ], [
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Form edit mapel.
     */
    public function edit(Subject $subject): View
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Perbarui data mapel.
     */
    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'name' => ['required', 'string', 'max:100'],
        ], [
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran ' . $subject->name . ' berhasil diperbarui.');
    }

    /**
     * Hapus mapel.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        $name = $subject->name;
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran ' . $name . ' berhasil dihapus.');
    }
}
