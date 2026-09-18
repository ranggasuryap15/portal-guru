{{--
==============================================================================
Tujuan: Halaman rekapitulasi leger nilai semester dan statistik kelas.
Dipakai Oleh: Guru\GradeController@recap (Route /guru/grades/{id}/recap)
Dependensi: layouts.app, TeachingAssignment, Grade, Student
Fungsi Utama: Menampilkan tabel leger nilai lengkap (UH 1-4, Ujian, Nilai Akhir) dan statistik kelas
Side Effect: Menampilkan rekapitulasi nilai
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Leger Nilai - ' . $assignment->subject->name)
@section('page-title', 'Leger Nilai Semester: ' . $assignment->subject->name . ' (' . $assignment->classroom->name . ')')

@section('content')
<div class="grid grid-4 no-print" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">📊</div>
        <div>
            <div class="stat-value">{{ $stats['avg'] }}</div>
            <div class="stat-label">Rata-rata Kelas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">🏆</div>
        <div>
            <div class="stat-value">{{ $stats['max'] }}</div>
            <div class="stat-label">Nilai Tertinggi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">📉</div>
        <div>
            <div class="stat-value">{{ $stats['min'] }}</div>
            <div class="stat-label">Nilai Terendah</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f8fafc; color: #475569;">👥</div>
        <div>
            <div class="stat-value">{{ $stats['count'] }} / {{ $students->count() }}</div>
            <div class="stat-label">Siswa Tuntas Dinilai</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3>Leger Nilai Hasil Belajar Siswa</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                Mata Pelajaran: <strong>{{ $assignment->subject->name }}</strong> | Kelas: <strong>{{ $assignment->classroom->name }}</strong> | Guru: <strong>{{ $assignment->teacher->name }}</strong> | Bobot: 60% UH + 40% Ujian
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">🖨️ Cetak Leger</button>
            <a href="{{ route('guru.grades.edit', $assignment->id) }}" class="btn btn-primary btn-sm">📝 Input / Edit Nilai</a>
            <a href="{{ route('guru.grades.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 45px;">No</th>
                    <th style="width: 120px;">NIS</th>
                    <th>Nama Lengkap Siswa</th>
                    <th style="text-align: center;">UH 1</th>
                    <th style="text-align: center;">UH 2</th>
                    <th style="text-align: center;">UH 3</th>
                    <th style="text-align: center;">UH 4</th>
                    <th style="text-align: center; background: #f8fafc;">Rata-rata UH</th>
                    <th style="text-align: center; color: #0284c7;">Ujian</th>
                    <th style="text-align: center; background: #e0f2fe; color: #0369a1;">Nilai Akhir</th>
                    <th style="text-align: center;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    @php
                        $grade = $student->grades->first();
                        $final = $grade ? $grade->final_score : null;
                        $avgUh = $grade ? $grade->average_uh : null;
                        $isPassed = !is_null($final) && $final >= 75; // KKM standar 75
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $student->nis }}</strong></td>
                        <td>
                            <div style="font-weight: 600;">{{ $student->name }}</div>
                        </td>
                        <td style="text-align: center;">{{ $grade && !is_null($grade->uh1) ? $grade->uh1 : '-' }}</td>
                        <td style="text-align: center;">{{ $grade && !is_null($grade->uh2) ? $grade->uh2 : '-' }}</td>
                        <td style="text-align: center;">{{ $grade && !is_null($grade->uh3) ? $grade->uh3 : '-' }}</td>
                        <td style="text-align: center;">{{ $grade && !is_null($grade->uh4) ? $grade->uh4 : '-' }}</td>
                        <td style="text-align: center; background: #f8fafc; font-weight: 600;">
                            {{ !is_null($avgUh) ? number_format($avgUh, 2) : '-' }}
                        </td>
                        <td style="text-align: center; font-weight: 600; color: #0284c7;">
                            {{ $grade && !is_null($grade->exam_score) ? $grade->exam_score : '-' }}
                        </td>
                        <td style="text-align: center; background: #f0f9ff; font-weight: 700; font-size: 1rem; color: #0369a1;">
                            {{ !is_null($final) ? number_format($final, 2) : '-' }}
                        </td>
                        <td style="text-align: center;">
                            @if(!is_null($final))
                                <span class="badge {{ $isPassed ? 'badge-success' : 'badge-danger' }}">
                                    {{ $isPassed ? 'Tuntas' : 'Remidi' }}
                                </span>
                            @else
                                <span class="badge badge-secondary">Belum Lengkap</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Tidak ada siswa di kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
