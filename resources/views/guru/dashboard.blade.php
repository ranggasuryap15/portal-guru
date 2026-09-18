{{--
==============================================================================
Tujuan: Halaman Dashboard Guru untuk melihat daftar kelas & mapel yang ditugaskan.
Dipakai Oleh: Guru\DashboardController@index (Route /guru/dashboard)
Dependensi: layouts.app, TeachingAssignment model
Fungsi Utama: Menampilkan ringkasan jam mengajar, kelas terdaftar, dan shortcut presensi/nilai
Side Effect: Menampilkan list tugas mengajar guru yang login
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru - Jadwal & Kelas Mengajar')

@section('content')
<div class="grid grid-3" style="margin-bottom: 28px;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">🏫</div>
        <div>
            <div class="stat-value">{{ $totalClasses }}</div>
            <div class="stat-label">Kelas yang Diampu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fdf2f8; color: #db2777;">📚</div>
        <div>
            <div class="stat-value">{{ $totalSubjects }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">🎒</div>
        <div>
            <div class="stat-value">{{ $totalStudents }}</div>
            <div class="stat-label">Total Siswa Diajar</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3>Daftar Kelas & Mata Pelajaran yang Anda Ampu</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                Anda hanya memiliki akses untuk mencatat presensi dan penilaian pada kelas dan mapel berikut.
            </p>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Tahun Ajaran / Semester</th>
                    <th>Jumlah Siswa</th>
                    <th>Aksi Cepat Presensi</th>
                    <th>Aksi Cepat Penilaian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td>
                            <strong style="font-size: 1.05rem; color: var(--primary);">{{ $assignment->classroom->name }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $assignment->subject->code }}</span>
                            <span style="font-weight: 600;">{{ $assignment->subject->name }}</span>
                        </td>
                        <td>
                            <div>{{ $assignment->academic_year }}</div>
                            <span class="badge {{ $assignment->semester === 'ganjil' ? 'badge-warning' : 'badge-success' }}">
                                Semester {{ ucfirst($assignment->semester) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-secondary" style="font-size: 0.85rem;">
                                👥 {{ $assignment->classroom->students->count() }} Siswa
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('guru.attendance.show', $assignment->id) }}" class="btn btn-primary btn-sm">
                                    📋 Absen Hari Ini
                                </a>
                                <a href="{{ route('guru.attendance.recap', $assignment->id) }}" class="btn btn-secondary btn-sm" title="Rekapitulasi Kehadiran">
                                    📊 Rekap
                                </a>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('guru.grades.edit', $assignment->id) }}" class="btn btn-primary btn-sm" style="background: #0284c7;">
                                    📝 Input Nilai
                                </a>
                                <a href="{{ route('guru.grades.recap', $assignment->id) }}" class="btn btn-secondary btn-sm" title="Rekap Nilai Semester">
                                    📈 Leger
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 36px;">
                            Anda belum didaftarkan ke kelas atau mata pelajaran apa pun oleh Administrator.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
