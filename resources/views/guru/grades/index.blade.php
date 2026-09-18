{{--
==============================================================================
Tujuan: Halaman daftar kelas untuk memilih modul penilaian oleh Guru.
Dipakai Oleh: Guru\GradeController@index (Route /guru/grades)
Dependensi: layouts.app, TeachingAssignment model
Fungsi Utama: Menampilkan pilihan kelas & mapel untuk input 4 UH dan nilai ujian
Side Effect: Menampilkan list penugasan
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Penilaian Siswa')
@section('page-title', 'Penilaian Siswa (Ulangan Harian & Ujian)')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Pilih Kelas & Mata Pelajaran</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                Input 4 Ulangan Harian (UH 1 - 4) dan Ujian Akhir Semester dengan bobot nilai akhir 60% UH + 40% Ujian.
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
                    <th>Jumlah Siswa Terdaftar</th>
                    <th>Status Input Nilai</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    @php
                        $gradedCount = $assignment->grades->filter(fn($g) => !is_null($g->final_score))->count();
                        $totalStudents = $assignment->classroom->students->count();
                    @endphp
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
                                👥 {{ $totalStudents }} Siswa
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $gradedCount >= $totalStudents && $totalStudents > 0 ? 'badge-success' : ($gradedCount > 0 ? 'badge-warning' : 'badge-secondary') }}">
                                {{ $gradedCount }} / {{ $totalStudents }} Siswa Terhitung
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 8px;">
                                <a href="{{ route('guru.grades.edit', $assignment->id) }}" class="btn btn-primary btn-sm">
                                    📝 Input / Edit Nilai
                                </a>
                                <a href="{{ route('guru.grades.recap', $assignment->id) }}" class="btn btn-secondary btn-sm">
                                    📈 Leger Nilai
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Anda belum memiliki penugasan kelas atau mata pelajaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
