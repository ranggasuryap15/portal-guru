<?php

/**
 * ==============================================================================
 * Tujuan: Controller CRUD Akun Guru, Relasi Multi-Mapel, dan Reset Password oleh Administrator.
 * Dipakai Oleh: routes/web.php (Route /admin/teachers/*)
 * Dependensi: App\Models\User, Hash
 * Daftar Fungsi: index(), create(), store(), edit(), update(), updatePassword(), destroy()
 * Side Effect: Insert, update, delete akun guru di tabel users
 * ==============================================================================
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Tampilkan daftar akun guru.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $teachers = User::where('role', 'guru')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->with('subjects')
            ->withCount('teachingAssignments')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.teachers.index', compact('teachers', 'search'));
    }

    /**
     * Tampilkan formulir tambah akun guru baru.
     */
    public function create(): View
    {
        return view('admin.teachers.create');
    }

    /**
     * Simpan akun guru baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:30', 'unique:users,nip'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama guru wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'password.required' => 'Password awal wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'guru',
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Akun guru baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit akun guru.
     */
    public function edit(User $teacher): View
    {
        abort_if($teacher->role !== 'guru', 404);

        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Perbarui data profil akun guru.
     */
    public function update(Request $request, User $teacher): RedirectResponse
    {
        abort_if($teacher->role !== 'guru', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($teacher->id)],
            'nip' => ['nullable', 'string', 'max:30', Rule::unique('users', 'nip')->ignore($teacher->id)],
            'phone' => ['nullable', 'string', 'max:25'],
        ], [
            'name.required' => 'Nama guru wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'nip.unique' => 'NIP sudah digunakan.',
        ]);

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Data guru '.$teacher->name.' berhasil diperbarui.');
    }

    /**
     * Ubah password akun guru oleh Admin.
     */
    public function updatePassword(Request $request, User $teacher): RedirectResponse
    {
        abort_if($teacher->role !== 'guru', 404);

        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $teacher->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password guru '.$teacher->name.' berhasil diperbarui.');
    }

    /**
     * Hapus akun guru.
     */
    public function destroy(User $teacher): RedirectResponse
    {
        abort_if($teacher->role !== 'guru', 404);

        $name = $teacher->name;
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Akun guru '.$name.' berhasil dihapus.');
    }
}
