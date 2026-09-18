{{--
==============================================================================
Tujuan: Formulir input presensi harian siswa responsif mobile per mapel dan kelas.
Dipakai Oleh: Guru\AttendanceController@show (Route /guru/attendances/{id})
Dependensi: layouts.app, TeachingAssignment, Attendance, Student
Fungsi Utama: Pemilihan tanggal, batch update status presensi touch-friendly (hadir/izin/sakit/alpa)
Side Effect: Simpan/update data presensi ke tabel attendances
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Input Presensi - ' . $assignment->subject->name)
@section('page-title', 'Presensi: ' . $assignment->subject->name . ' (' . $assignment->classroom->name . ')')

@section('content')
<style>
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        font-weight: 600;
        user-select: none;
        transition: all 0.15s ease;
    }
    .status-pill:hover {
        background: #f1f5f9;
    }
    @media (max-width: 768px) {
        .attendance-toolbar {
            flex-direction: column;
            align-items: stretch !important;
        }
        .attendance-toolbar form,
        .attendance-toolbar div,
        .attendance-toolbar button {
            width: 100%;
        }
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
                Tahun Ajaran: {{ $assignment->academic_year }} | Semester: {{ ucfirst($assignment->semester) }}
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('guru.attendance.recap', $assignment->id) }}" class="btn btn-secondary btn-sm">
                📊 Rekap Semester
            </a>
            <a href="{{ route('guru.attendance.index') }}" class="btn btn-secondary btn-sm">
                ⬅ Kembali
            </a>
        </div>
    </div>

    <!-- Filter Tanggal Presensi -->
    <div class="attendance-toolbar" style="padding: 14px 20px; background: #f8fafc; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <form action="{{ route('guru.attendance.show', $assignment->id) }}" method="GET" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <label for="date" style="margin-bottom: 0; font-size: 0.88rem; white-space: nowrap;">📅 Tanggal Presensi:</label>
            <input type="date" id="date" name="date" value="{{ $date }}" style="width: auto; padding: 7px 12px;" onchange="this.form.submit()">
            <button type="submit" class="btn btn-secondary btn-sm">Pilih Tanggal</button>
        </form>

        <div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="setAllAttendance('hadir')">
                ✅ Tandai Semua Hadir
            </button>
        </div>
    </div>

    <form action="{{ route('guru.attendance.store', $assignment->id) }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 140px;">NIS</th>
                        <th>Nama Siswa</th>
                        <th>L/P</th>
                        <th style="min-width: 330px; text-align: center;">Status Kehadiran</th>
                        <th style="min-width: 200px;">Catatan (Opsional)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $currentStatus = old("attendance.{$student->id}.status", $existingAttendances[$student->id]->status ?? 'hadir');
                            $currentNotes = old("attendance.{$student->id}.notes", $existingAttendances[$student->id]->notes ?? '');
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $student->nis }}</strong></td>
                            <td>
                                <div style="font-weight: 600;">{{ $student->name }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $student->gender === 'L' ? 'badge-info' : 'badge-warning' }}" style="font-size: 0.7rem;">
                                    {{ $student->gender }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; justify-content: center; gap: 8px; font-size: 0.85rem; flex-wrap: wrap;">
                                    <label class="status-pill">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]" value="hadir" class="status-radio-hadir" {{ $currentStatus === 'hadir' ? 'checked' : '' }}>
                                        <span style="color: #16a34a;">Hadir</span>
                                    </label>
                                    <label class="status-pill">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]" value="izin" class="status-radio-izin" {{ $currentStatus === 'izin' ? 'checked' : '' }}>
                                        <span style="color: #0284c7;">Izin</span>
                                    </label>
                                    <label class="status-pill">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]" value="sakit" class="status-radio-sakit" {{ $currentStatus === 'sakit' ? 'checked' : '' }}>
                                        <span style="color: #d97706;">Sakit</span>
                                    </label>
                                    <label class="status-pill">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]" value="alpa" class="status-radio-alpa" {{ $currentStatus === 'alpa' ? 'checked' : '' }}>
                                        <span style="color: #dc2626;">Alpa</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="attendance[{{ $student->id }}][notes]" value="{{ $currentNotes }}" placeholder="Keterangan (misal: surat dokter)" style="padding: 6px 10px; font-size: 0.85rem;">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                Tidak ada data siswa di kelas {{ $assignment->classroom->name }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->isNotEmpty())
            <div class="card-footer-action" style="padding: 18px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    Menampilkan {{ $students->count() }} siswa terdaftar di kelas {{ $assignment->classroom->name }}.
                </span>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-size: 0.95rem;">
                    💾 Simpan Presensi Tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                </button>
            </div>
        @endif
    </form>
</div>

<script>
    function setAllAttendance(status) {
        const radios = document.querySelectorAll('.status-radio-' + status);
        radios.forEach(radio => radio.checked = true);
    }
</script>
@endsection
