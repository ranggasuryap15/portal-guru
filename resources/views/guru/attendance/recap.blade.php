{{--
==============================================================================
Tujuan: Halaman rekapitulasi kehadiran siswa dalam 1 semester.
Dipakai Oleh: Guru\AttendanceController@recap (Route /guru/attendances/{id}/recap)
Dependensi: layouts.app, TeachingAssignment, Student, Attendance
Fungsi Utama: Menampilkan ringkasan total hadir, izin, sakit, alpa, dan % kehadiran
Side Effect: Menampilkan agregasi data presensi
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Rekap Presensi - ' . $assignment->subject->name)
@section('page-title', 'Rekapitulasi Presensi: ' . $assignment->subject->name . ' (' . $assignment->classroom->name . ')')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Kelas {{ $assignment->classroom->name }} | Mapel: {{ $assignment->subject->name }}</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                Tahun Ajaran: {{ $assignment->academic_year }} | Semester: {{ ucfirst($assignment->semester) }} | Total Pertemuan Tercatat: <strong>{{ $totalMeetings }}</strong> kali
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">🖨️ Cetak Rekap</button>
            <a href="{{ route('guru.attendance.show', $assignment->id) }}" class="btn btn-primary btn-sm">📋 Absen Hari Ini</a>
            <a href="{{ route('guru.attendance.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 130px;">NIS</th>
                    <th>Nama Lengkap Siswa</th>
                    <th style="text-align: center; color: #16a34a;">Hadir</th>
                    <th style="text-align: center; color: #0284c7;">Izin</th>
                    <th style="text-align: center; color: #d97706;">Sakit</th>
                    <th style="text-align: center; color: #dc2626;">Alpa</th>
                    <th style="text-align: center;">Persentase Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    @php
                        $hadir = $student->attendances->where('status', 'hadir')->count();
                        $izin = $student->attendances->where('status', 'izin')->count();
                        $sakit = $student->attendances->where('status', 'sakit')->count();
                        $alpa = $student->attendances->where('status', 'alpa')->count();
                        $percentage = $totalMeetings > 0 ? round(($hadir / $totalMeetings) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $student->nis }}</strong></td>
                        <td>
                            <div style="font-weight: 600;">{{ $student->name }}</div>
                        </td>
                        <td style="text-align: center; font-weight: 600; color: #16a34a;">{{ $hadir }}</td>
                        <td style="text-align: center; font-weight: 600; color: #0284c7;">{{ $izin }}</td>
                        <td style="text-align: center; font-weight: 600; color: #d97706;">{{ $sakit }}</td>
                        <td style="text-align: center; font-weight: 600; color: #dc2626;">{{ $alpa }}</td>
                        <td style="text-align: center;">
                            <span class="badge {{ $percentage >= 80 ? 'badge-success' : ($percentage >= 60 ? 'badge-warning' : 'badge-danger') }}">
                                {{ $percentage }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Tidak ada siswa terdaftar di kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
