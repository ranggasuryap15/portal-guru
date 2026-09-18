<?php

/**
 * ==============================================================================
 * Tujuan: Controller Presensi Harian Siswa per Mapel dan Kelas oleh Guru Pengampu.
 * Dipakai Oleh: routes/web.php (Route /guru/attendances/*)
 * Dependensi: Illuminate\Support\Facades\Auth, App\Models\TeachingAssignment, Attendance, Student
 * Daftar Fungsi: index(), show(), store(), recap()
 * Side Effect: Insert dan update batch data presensi di tabel attendances
 * ==============================================================================
 */

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\TeachingAssignment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Tampilkan daftar mapel/kelas yang diampu untuk presensi.
     */
    public function index(): View
    {
        $assignments = TeachingAssignment::where('teacher_id', Auth::id())
            ->with(['classroom.students', 'subject'])
            ->latest()
            ->get();

        return view('guru.attendance.index', compact('assignments'));
    }

    /**
     * Tampilkan formulir presensi siswa untuk tanggal tertentu.
     */
    public function show(Request $request, TeachingAssignment $assignment): View
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403, 'Anda bukan guru pengampu kelas/mapel ini.');

        $date = $request->query('date', Carbon::today()->toDateString());

        // Ambil semua siswa di kelas ini
        $students = Student::where('classroom_id', $assignment->classroom_id)
            ->orderBy('name')
            ->get();

        // Ambil presensi yang sudah tersimpan untuk tanggal ini
        $existingAttendances = Attendance::where('teaching_assignment_id', $assignment->id)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        // Riwayat 5 tanggal presensi terakhir
        $recentDates = Attendance::where('teaching_assignment_id', $assignment->id)
            ->select('date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->take(5)
            ->pluck('date');

        return view('guru.attendance.show', compact('assignment', 'students', 'date', 'existingAttendances', 'recentDates'));
    }

    /**
     * Simpan / Perbarui data presensi siswa per tanggal secara batch.
     */
    public function store(Request $request, TeachingAssignment $assignment): RedirectResponse
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', 'in:hadir,izin,sakit,alpa'],
            'attendance.*.notes' => ['nullable', 'string', 'max:255'],
        ], [
            'date.required' => 'Tanggal presensi wajib dipilih.',
            'attendance.required' => 'Data presensi siswa wajib diisi.',
        ]);

        $date = $validated['date'];

        DB::transaction(function () use ($assignment, $date, $validated) {
            foreach ($validated['attendance'] as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'teaching_assignment_id' => $assignment->id,
                        'student_id' => $studentId,
                        'date' => $date,
                    ],
                    [
                        'status' => $data['status'],
                        'notes' => $data['notes'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('guru.attendance.show', [$assignment->id, 'date' => $date])
            ->with('success', 'Presensi siswa tanggal ' . Carbon::parse($date)->translatedFormat('d F Y') . ' berhasil disimpan.');
    }

    /**
     * Tampilkan rekapitulasi kehadiran siswa dalam 1 semester.
     */
    public function recap(TeachingAssignment $assignment): View
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $students = Student::where('classroom_id', $assignment->classroom_id)
            ->with(['attendances' => fn ($q) => $q->where('teaching_assignment_id', $assignment->id)])
            ->orderBy('name')
            ->get();

        $totalMeetings = Attendance::where('teaching_assignment_id', $assignment->id)
            ->distinct('date')
            ->count('date');

        return view('guru.attendance.recap', compact('assignment', 'students', 'totalMeetings'));
    }
}
