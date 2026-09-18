<?php

/**
 * ==============================================================================
 * Tujuan: Seeder database awal untuk pengguna (Admin & Guru), kelas, mapel, siswa, dan penugasan.
 * Dipakai Oleh: DatabaseSeeder, Artisan db:seed
 * Dependensi: User, Classroom, Subject, Student, TeachingAssignment, Attendance, Grade
 * Daftar Fungsi: run()
 * Side Effect: Insert initial data ke database MySQL portal_guru
 * ==============================================================================
 */

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AcademicSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        // 1. Buat Akun Administrator
        $admin = User::firstOrCreate(
            ['email' => 'admin@portalguru.test'],
            [
                'name' => 'Administrator Portal',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'phone' => '081100000001',
            ]
        );

        // 2. Buat Akun Guru
        $guru1 = User::firstOrCreate(
            ['email' => 'guru@portalguru.test'],
            [
                'name' => 'Dra. Siti Aminah, M.Pd.',
                'role' => 'guru',
                'nip' => '197508152000032001',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
            ]
        );

        $guru2 = User::firstOrCreate(
            ['email' => 'bambang@portalguru.test'],
            [
                'name' => 'Bambang Hidayat, S.Si.',
                'role' => 'guru',
                'nip' => '198203102008011005',
                'phone' => '081298765432',
                'password' => Hash::make('password'),
            ]
        );

        // 3. Buat Kelas
        $kelas1 = Classroom::firstOrCreate(['name' => 'X-IPA-1', 'academic_year' => '2026/2027']);
        $kelas2 = Classroom::firstOrCreate(['name' => 'X-IPA-2', 'academic_year' => '2026/2027']);
        $kelas3 = Classroom::firstOrCreate(['name' => 'XI-IPA-1', 'academic_year' => '2026/2027']);

        // 4. Buat Mata Pelajaran
        $mapelMat = Subject::firstOrCreate(['code' => 'MAT-10'], ['name' => 'Matematika Wajib']);
        $mapelFis = Subject::firstOrCreate(['code' => 'FIS-10'], ['name' => 'Fisika Dasar']);
        $mapelBin = Subject::firstOrCreate(['code' => 'BIN-10'], ['name' => 'Bahasa Indonesia']);

        // 5. Buat Penugasan Guru (Satu guru mengajar mapel di beberapa kelas)
        // Siti Aminah mengajar Matematika di X-IPA-1 dan X-IPA-2
        $assignMat1 = TeachingAssignment::firstOrCreate([
            'teacher_id' => $guru1->id,
            'classroom_id' => $kelas1->id,
            'subject_id' => $mapelMat->id,
            'academic_year' => '2026/2027',
            'semester' => 'ganjil',
        ]);

        $assignMat2 = TeachingAssignment::firstOrCreate([
            'teacher_id' => $guru1->id,
            'classroom_id' => $kelas2->id,
            'subject_id' => $mapelMat->id,
            'academic_year' => '2026/2027',
            'semester' => 'ganjil',
        ]);

        // Bambang Hidayat mengajar Fisika di X-IPA-1
        $assignFis1 = TeachingAssignment::firstOrCreate([
            'teacher_id' => $guru2->id,
            'classroom_id' => $kelas1->id,
            'subject_id' => $mapelFis->id,
            'academic_year' => '2026/2027',
            'semester' => 'ganjil',
        ]);

        // 6. Buat Siswa di Kelas X-IPA-1
        $studentsX1 = [
            ['nis' => '20261001', 'name' => 'Aditia Pratama', 'gender' => 'L', 'nisn' => '0081234501'],
            ['nis' => '20261002', 'name' => 'Bella Safitri', 'gender' => 'P', 'nisn' => '0081234502'],
            ['nis' => '20261003', 'name' => 'Dimas Anggara', 'gender' => 'L', 'nisn' => '0081234503'],
            ['nis' => '20261004', 'name' => 'Erna Lestari', 'gender' => 'P', 'nisn' => '0081234504'],
            ['nis' => '20261005', 'name' => 'Fajar Nugraha', 'gender' => 'L', 'nisn' => '0081234505'],
        ];

        $createdStudentsX1 = [];
        foreach ($studentsX1 as $data) {
            $createdStudentsX1[] = Student::firstOrCreate(
                ['nis' => $data['nis']],
                array_merge($data, ['classroom_id' => $kelas1->id])
            );
        }

        // Buat Siswa di Kelas X-IPA-2
        $studentsX2 = [
            ['nis' => '20261006', 'name' => 'Gita Permata', 'gender' => 'P', 'nisn' => '0081234506'],
            ['nis' => '20261007', 'name' => 'Hendra Wijaya', 'gender' => 'L', 'nisn' => '0081234507'],
            ['nis' => '20261008', 'name' => 'Indah Kusuma', 'gender' => 'P', 'nisn' => '0081234508'],
        ];

        foreach ($studentsX2 as $data) {
            Student::firstOrCreate(
                ['nis' => $data['nis']],
                array_merge($data, ['classroom_id' => $kelas2->id])
            );
        }

        // 7. Contoh Data Presensi Awal (Hari ini untuk X-IPA-1 di mapel Matematika)
        $today = Carbon::today()->toDateString();
        foreach ($createdStudentsX1 as $idx => $student) {
            $status = $idx === 1 ? 'izin' : ($idx === 2 ? 'sakit' : 'hadir');
            Attendance::firstOrCreate(
                [
                    'teaching_assignment_id' => $assignMat1->id,
                    'student_id' => $student->id,
                    'date' => $today,
                ],
                [
                    'status' => $status,
                    'notes' => $status !== 'hadir' ? 'Izin kegiatan' : null,
                ]
            );
        }

        // 8. Contoh Nilai 4 UH + Ujian untuk Aditia Pratama & Bella Safitri
        // Aditia: UH 80, 85, 90, 85 (Avg 85). Ujian 85 -> Final: (85 * 0.6) + (85 * 0.4) = 85.00
        Grade::updateOrCreate(
            [
                'teaching_assignment_id' => $assignMat1->id,
                'student_id' => $createdStudentsX1[0]->id,
            ],
            [
                'uh1' => 80,
                'uh2' => 85,
                'uh3' => 90,
                'uh4' => 85,
                'exam_score' => 85,
                'final_score' => 85.00,
            ]
        );

        // Bella: UH 90, 90, 95, 95 (Avg 92.5). Ujian 90 -> Final: (92.5 * 0.6) + (90 * 0.4) = 55.5 + 36 = 91.50
        Grade::updateOrCreate(
            [
                'teaching_assignment_id' => $assignMat1->id,
                'student_id' => $createdStudentsX1[1]->id,
            ],
            [
                'uh1' => 90,
                'uh2' => 90,
                'uh3' => 95,
                'uh4' => 95,
                'exam_score' => 90,
                'final_score' => 91.50,
            ]
        );
    }
}
