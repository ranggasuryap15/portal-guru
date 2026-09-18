{{--
==============================================================================
Tujuan: Halaman Dashboard Administrator untuk ringkasan data akademik.
Dipakai Oleh: Admin\DashboardController@index (Route /admin/dashboard)
Dependensi: layouts.app, Carbon
Fungsi Utama: Menampilkan total guru, siswa, kelas, mapel, penugasan, dan tabel aktivitas terbaru
Side Effect: Menampilkan metrik data
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Dashboard Administrator')
@section('page-title', 'Dashboard Administrator')

@section('content')
<div class="grid grid-4" style="margin-bottom: 28px;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">👨‍🏫</div>
        <div>
            <div class="stat-value">{{ $teacherCount }}</div>
            <div class="stat-label">Total Guru</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">🎒</div>
        <div>
            <div class="stat-value">{{ $studentCount }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fefce8; color: #ca8a04;">🏫</div>
        <div>
            <div class="stat-value">{{ $classroomCount }}</div>
            <div class="stat-label">Total Kelas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fdf2f8; color: #db2777;">📚</div>
        <div>
            <div class="stat-value">{{ $subjectCount }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Akses Cepat Pengelolaan</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">➕ Tambah Guru</a>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">➕ Tambah Siswa</a>
            <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary">➕ Daftarkan Guru ke Mapel & Kelas</a>
            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-secondary">➕ Tambah Kelas</a>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-secondary">➕ Tambah Mapel</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Penugasan Mengajar Terbaru</h3>
        <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary btn-sm">Lihat Semua Penugasan</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Guru Pengampu</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAssignments as $assignment)
                    <tr>
                        <td>
                            <strong>{{ $assignment->teacher->name }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">NIP: {{ $assignment->teacher->nip ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $assignment->subject->code }}</span>
                            {{ $assignment->subject->name }}
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $assignment->classroom->name }}</span>
                        </td>
                        <td>{{ $assignment->academic_year }}</td>
                        <td>
                            <span class="badge {{ $assignment->semester === 'ganjil' ? 'badge-warning' : 'badge-success' }}">
                                {{ ucfirst($assignment->semester) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada data penugasan guru. Silakan daftarkan guru ke mata pelajaran dan kelas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
