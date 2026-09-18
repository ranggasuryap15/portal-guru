<?php

/**
 * ==============================================================================
 * Tujuan: Feature Test untuk memverifikasi alur bisnis Portal Guru.
 * Dipakai Oleh: PHPUnit / php artisan test
 * Dependensi: Tests\TestCase, User, Classroom, Subject, TeachingAssignment, Grade
 * Daftar Test: test_login_flow(), test_guru_cannot_access_admin(), test_grade_calculation()
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
use Illuminate\Support\Facades\Hash;
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
}
