<?php

/**
 * ==============================================================================
 * Tujuan: Feature Test untuk memverifikasi alur bisnis Portal Guru dan penugasan multi-mapel.
 * Dipakai Oleh: PHPUnit / php artisan test
 * Dependensi: Tests\TestCase, User, Classroom, Subject, TeachingAssignment, Grade
 * Daftar Test: test_login_flow(), test_guru_cannot_access_admin(), test_grade_calculation(), test_one_teacher_can_teach_multiple_subjects()
 * Side Effect: Query DB saat testing
 * ==============================================================================
 */

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Tests\TestCase;

class PortalGuruTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Portal Guru');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrator');
    }

    public function test_guru_can_access_own_dashboard(): void
    {
        $guru = User::where('role', 'guru')->first();
        $response = $this->actingAs($guru)->get('/guru/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Guru');
    }

    public function test_guru_cannot_access_admin_dashboard(): void
    {
        $guru = User::where('role', 'guru')->first();
        $response = $this->actingAs($guru)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_grade_calculation_formula_60_40(): void
    {
        $assignment = TeachingAssignment::first();
        $student = Student::first();

        // UH 1=80, UH 2=80, UH 3=80, UH 4=80 -> Avg = 80
        // Exam = 90
        // Final = (80 * 0.6) + (90 * 0.4) = 48 + 36 = 84.00
        $grade = Grade::updateOrCreate(
            ['teaching_assignment_id' => $assignment->id, 'student_id' => $student->id],
            ['uh1' => 80, 'uh2' => 80, 'uh3' => 80, 'uh4' => 80, 'exam_score' => 90]
        );

        $this->assertEquals(84.00, $grade->final_score);
    }

    public function test_one_teacher_can_teach_multiple_subjects(): void
    {
        $admin = User::where('role', 'admin')->first();
        $guru = User::where('role', 'guru')->first();
        $classrooms = Classroom::take(2)->get();
        $subjects = Subject::take(2)->get();

        // 1. Admin menugaskan guru ke beberapa mata pelajaran sekaligus via POST /admin/assignments
        $csrfToken = 'test-csrf-token-12345';
        $response = $this->actingAs($admin)
            ->withSession(['_token' => $csrfToken])
            ->post('/admin/assignments', [
                '_token' => $csrfToken,
                'teacher_id' => $guru->id,
                'subject_ids' => $subjects->pluck('id')->toArray(),
                'classroom_ids' => $classrooms->pluck('id')->toArray(),
                'academic_year' => '2026/2027',
                'semester' => 'ganjil',
            ]);

        $response->assertRedirect(route('admin.assignments.index'));
        $response->assertSessionHas('success');

        // 2. Verifikasi data di tabel teaching_assignments terbentuk untuk semua kombinasi
        foreach ($subjects as $subject) {
            foreach ($classrooms as $classroom) {
                $this->assertDatabaseHas('teaching_assignments', [
                    'teacher_id' => $guru->id,
                    'classroom_id' => $classroom->id,
                    'subject_id' => $subject->id,
                ]);
            }
        }

        // 3. Verifikasi relasi direct $guru->subjects mengembalikan semua mapel unik
        $guruSubjects = $guru->fresh()->subjects;
        $this->assertGreaterThanOrEqual(2, $guruSubjects->count());

        // 4. Verifikasi halaman dashboard guru menampilkan jumlah mapel
        $dashboardResponse = $this->actingAs($guru)->get('/guru/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Mata Pelajaran');
    }
}
