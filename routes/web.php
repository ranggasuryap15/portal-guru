<?php

/**
 * ==============================================================================
 * Tujuan: Definisi rute web aplikasi Portal Guru untuk Role Admin dan Guru.
 * Dipakai Oleh: HTTP Kernel, Service Provider Route
 * Dependensi: AuthController, Admin\* Controllers, Guru\* Controllers, RoleMiddleware
 * Daftar Route: /login, /logout, /admin/* (Dashboard, Guru, Kelas, Mapel, Siswa, Penugasan), /guru/* (Dashboard, Presensi, Nilai)
 * Side Effect: Memetakan HTTP Request ke Controller actions
 * ==============================================================================
 */

use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeachingAssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\AttendanceController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\GradeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root ke halaman dashboard atau login
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('guru.dashboard');
    }
    return redirect()->route('login');
});

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// RUTE KHUSUS ADMINISTRATOR
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Guru & Ubah Password
    Route::resource('teachers', TeacherController::class);
    Route::put('/teachers/{teacher}/password', [TeacherController::class, 'updatePassword'])->name('teachers.password');

    // CRUD Kelas
    Route::resource('classrooms', ClassroomController::class);

    // CRUD Mata Pelajaran
    Route::resource('subjects', SubjectController::class);

    // Mendaftarkan Guru ke Mapel & Kelas
    Route::resource('assignments', TeachingAssignmentController::class)->only(['index', 'create', 'store', 'destroy']);

    // CRUD Siswa & Assign Kelas
    Route::resource('students', StudentController::class);
});

// ==========================================
// RUTE KHUSUS GURU
// ==========================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Presensi Siswa per Hari per Mapel
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendances/{assignment}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendances/{assignment}', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendances/{assignment}/recap', [AttendanceController::class, 'recap'])->name('attendance.recap');

    // Penilaian Siswa (4 UH + Ujian Akhir Semester: 60% UH + 40% Ujian)
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{assignment}', [GradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{assignment}', [GradeController::class, 'update'])->name('grades.update');
    Route::get('/grades/{assignment}/recap', [GradeController::class, 'recap'])->name('grades.recap');
});
