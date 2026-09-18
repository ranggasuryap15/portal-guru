<?php

/**
 * ==============================================================================
 * Tujuan: Controller Dashboard Administrator Portal Guru.
 * Dipakai Oleh: routes/web.php (Route /admin/dashboard)
 * Dependensi: User, Student, Classroom, Subject, TeachingAssignment
 * Daftar Fungsi: index()
 * Side Effect: Query agregasi count dan listing relasi penugasan terbaru
 * ==============================================================================
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan statistik dan ringkasan akademik untuk Administrator.
     */
    public function index(): View
    {
        $teacherCount = User::where('role', 'guru')->count();
        $studentCount = Student::count();
        $classroomCount = Classroom::count();
        $subjectCount = Subject::count();
        $assignmentCount = TeachingAssignment::count();

        $recentAssignments = TeachingAssignment::with(['teacher', 'classroom', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'teacherCount',
            'studentCount',
            'classroomCount',
            'subjectCount',
            'assignmentCount',
            'recentAssignments'
        ));
    }
}
