{{--
==============================================================================
Tujuan: Halaman daftar kelas untuk memilih presensi siswa oleh Guru.
Dipakai Oleh: Guru\AttendanceController@index (Route /guru/attendances)
Dependensi: layouts.app, TeachingAssignment model
Fungsi Utama: Menampilkan pilihan kelas & mapel yang diampu untuk absensi
Side Effect: Menampilkan list penugasan
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Presensi Siswa')
@section('page-title', 'Presensi Harian Siswa per Mata Pelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Pilih Kelas & Mata Pelajaran</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                Pilih kelas dan mata pelajaran untuk mencatat kehadiran harian siswa.
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
                    <th>Total Siswa</th>
                    <th style="text-align: right;">Aksi</th>
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
                            <strong>{{ $assignment->subject->name }}</strong>
                        </td>
                        <td>
                            {{ $assignment->academic_year }} (Semester {{ ucfirst($assignment->semester) }})
                        </td>
                        <td>
                            <span class="badge badge-secondary">
                                👥 {{ $assignment->classroom->students->count() }} Siswa
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 8px;">
                                <a href="{{ route('guru.attendance.show', $assignment->id) }}" class="btn btn-primary btn-sm">
                                    📋 Input Presensi
                                </a>
                                <a href="{{ route('guru.attendance.recap', $assignment->id) }}" class="btn btn-secondary btn-sm">
                                    📊 Rekap Kehadiran
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Anda belum memiliki penugasan kelas atau mata pelajaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
