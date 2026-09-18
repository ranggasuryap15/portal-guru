{{--
==============================================================================
Tujuan: Formulir input nilai 4 Ulangan Harian & Ujian responsif mobile (60% UH + 40% Ujian).
Dipakai Oleh: Guru\GradeController@edit (Route /guru/grades/{id})
Dependensi: layouts.app, TeachingAssignment, Grade, Student
Fungsi Utama: Input form batch UH 1-4 & Ujian touch-friendly, kalkulasi real-time nilai akhir
Side Effect: Simpan/update nilai ke tabel grades
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Input Nilai - ' . $assignment->subject->name)
@section('page-title', 'Penilaian: ' . $assignment->subject->name . ' (' . $assignment->classroom->name . ')')

@section('content')
<style>
    .score-input {
        min-width: 68px;
    }
    @media (max-width: 768px) {
        .card-footer-action {
            flex-direction: column;
            align-items: stretch !important;
            text-align: center;
            padding: 16px !important;
        }
        .card-footer-action button {
            width: 100%;
        }
    }
</style>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h3>Kelas: {{ $assignment->classroom->name }} | Mapel: {{ $assignment->subject->name }}</h3>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">
                Formula Perhitungan: <strong>Nilai Akhir = (60% × Rata-rata 4 UH) + (40% × Nilai Ujian)</strong>
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('guru.grades.recap', $assignment->id) }}" class="btn btn-secondary btn-sm">
                📈 Leger Nilai
            </a>
            <a href="{{ route('guru.grades.index') }}" class="btn btn-secondary btn-sm">
                ⬅ Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('guru.grades.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 45px;">No</th>
                        <th style="width: 130px;">NIS</th>
                        <th style="min-width: 180px;">Nama Lengkap Siswa</th>
                        <th style="width: 105px; text-align: center;">UH 1 (0-100)</th>
                        <th style="width: 105px; text-align: center;">UH 2 (0-100)</th>
                        <th style="width: 105px; text-align: center;">UH 3 (0-100)</th>
                        <th style="width: 105px; text-align: center;">UH 4 (0-100)</th>
                        <th style="width: 105px; text-align: center; background: #f1f5f9;">Rata-rata UH</th>
                        <th style="width: 115px; text-align: center; color: #0284c7;">Ujian (0-100)</th>
                        <th style="width: 125px; text-align: center; background: #e0f2fe; color: #0369a1;">Nilai Akhir (60:40)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $grade = $student->grades->first();
                            $uh1 = old("grades.{$student->id}.uh1", $grade->uh1 ?? '');
                            $uh2 = old("grades.{$student->id}.uh2", $grade->uh2 ?? '');
                            $uh3 = old("grades.{$student->id}.uh3", $grade->uh3 ?? '');
                            $uh4 = old("grades.{$student->id}.uh4", $grade->uh4 ?? '');
                            $exam = old("grades.{$student->id}.exam_score", $grade->exam_score ?? '');
                            $final = $grade->final_score ?? null;
                            $avgUh = $grade ? $grade->average_uh : null;
                        @endphp
                        <tr class="student-grade-row" data-student-id="{{ $student->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $student->nis }}</strong></td>
                            <td>
                                <div style="font-weight: 600;">{{ $student->name }}</div>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" max="100" inputmode="decimal" name="grades[{{ $student->id }}][uh1]" value="{{ $uh1 }}" class="score-input input-uh1" style="text-align: center; padding: 6px 4px;">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" max="100" inputmode="decimal" name="grades[{{ $student->id }}][uh2]" value="{{ $uh2 }}" class="score-input input-uh2" style="text-align: center; padding: 6px 4px;">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" max="100" inputmode="decimal" name="grades[{{ $student->id }}][uh3]" value="{{ $uh3 }}" class="score-input input-uh3" style="text-align: center; padding: 6px 4px;">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" max="100" inputmode="decimal" name="grades[{{ $student->id }}][uh4]" value="{{ $uh4 }}" class="score-input input-uh4" style="text-align: center; padding: 6px 4px;">
                            </td>
                            <td style="text-align: center; background: #f8fafc; font-weight: 600;">
                                <span class="preview-avg-uh">{{ !is_null($avgUh) ? number_format($avgUh, 2) : '-' }}</span>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" max="100" inputmode="decimal" name="grades[{{ $student->id }}][exam_score]" value="{{ $exam }}" class="score-input input-exam" style="text-align: center; padding: 6px 4px; border-color: #93c5fd;">
                            </td>
                            <td style="text-align: center; background: #f0f9ff; font-weight: 700; font-size: 1rem; color: #0284c7;">
                                <span class="preview-final-score">{{ !is_null($final) ? number_format($final, 2) : '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                Tidak ada data siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->isNotEmpty())
            <div class="card-footer-action" style="padding: 18px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 0.85rem; color: var(--text-muted);">
                    💡 Nilai Akhir akan dihitung secara otomatis oleh sistem saat disimpan (60% Ulangan Harian + 40% Ujian).
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 10px 28px; font-size: 0.95rem;">
                    💾 Simpan Seluruh Nilai
                </button>
            </div>
        @endif
    </form>
</div>

<script>
    // Real-time calculation preview saat guru mengetikkan nilai
    document.querySelectorAll('.score-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.student-grade-row');
            calculateRow(row);
        });
    });

    function calculateRow(row) {
        const uh1 = parseFloat(row.querySelector('.input-uh1').value);
        const uh2 = parseFloat(row.querySelector('.input-uh2').value);
        const uh3 = parseFloat(row.querySelector('.input-uh3').value);
        const uh4 = parseFloat(row.querySelector('.input-uh4').value);
        const exam = parseFloat(row.querySelector('.input-exam').value);

        const uhList = [uh1, uh2, uh3, uh4].filter(v => !isNaN(v) && v !== null);

        let avgUh = null;
        if (uhList.length > 0) {
            avgUh = uhList.reduce((a, b) => a + b, 0) / uhList.length;
            row.querySelector('.preview-avg-uh').textContent = avgUh.toFixed(2);
        } else {
            row.querySelector('.preview-avg-uh').textContent = '-';
        }

        if (avgUh !== null || !isNaN(exam)) {
            const uhPart = (avgUh !== null ? avgUh : 0) * 0.60;
            const examPart = (!isNaN(exam) ? exam : 0) * 0.40;
            const finalScore = uhPart + examPart;
            row.querySelector('.preview-final-score').textContent = finalScore.toFixed(2);
        } else {
            row.querySelector('.preview-final-score').textContent = '-';
        }
    }
</script>
@endsection
