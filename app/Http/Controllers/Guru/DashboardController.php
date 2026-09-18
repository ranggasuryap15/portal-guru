<?php

/**
 * ==============================================================================
 * Tujuan: Controller Dashboard Guru untuk melihat daftar kelas dan mapel yang diampu.
 * Dipakai Oleh: routes/web.php (Route /guru/dashboard)
 * Dependensi: Illuminate\Support\Facades\Auth, App\Models\TeachingAssignment
 * Daftar Fungsi: index()
 * Side Effect: Query data penugasan mengajar khusus guru yang sedang login
 * ==============================================================================
 */

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TeachingAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Guru dengan daftar kelas dan mapel yang terdaftar.
     */
    public function index(): View
    {
        $teacher = Auth::user();

        // Ambil hanya penugasan mengajar milik guru yang sedang login
        $assignments = TeachingAssignment::where('teacher_id', $teacher->id)
            ->with(['classroom.students', 'subject'])
            ->withCount(['attendances', 'grades'])
            ->orderBy('academic_year', 'desc')
            ->get();

        // Hitung total unik kelas, mapel, dan siswa
        $totalClasses = $assignments->pluck('classroom_id')->unique()->count();
        $totalSubjects = $assignments->pluck('subject_id')->unique()->count();
        
        $totalStudents = $assignments->pluck('classroom.students')
            ->flatten()
            ->unique('id')
            ->count();

        return view('guru.dashboard', compact(
            'teacher',
            'assignments',
            'totalClasses',
            'totalSubjects',
            'totalStudents'
        ));
    }
}
