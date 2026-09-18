{{--
==============================================================================
Tujuan: Halaman formulir pendaftaran guru ke beberapa mata pelajaran dan beberapa kelas sekaligus.
Dipakai Oleh: Admin\TeachingAssignmentController@create (Route /admin/assignments/create)
Dependensi: layouts.app, User, Classroom, Subject
Fungsi Utama: Pemilihan guru, multi-pilihan mapel, multi-pilihan kelas, tahun ajaran, dan semester
Side Effect: Pengiriman data POST ke /admin/assignments
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Daftarkan Guru ke Mapel & Kelas')
@section('page-title', 'Formulir Penugasan Guru ke Mata Pelajaran & Kelas')

@section('content')
<div style="max-width: 720px;">
    <div class="card">
        <div class="card-header">
            <div>
                <h3>Penugasan Guru ke Mapel & Kelas</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                    Pilih guru pengampu, centang satu atau lebih mata pelajaran, dan centang satu atau lebih kelas yang akan diajar.
                </p>
            </div>
            <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.assignments.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="teacher_id">Pilih Guru Pengampu <span style="color: var(--danger);">*</span></label>
                    <select id="teacher_id" name="teacher_id" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} {{ $teacher->nip ? '(NIP: ' . $teacher->nip . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label style="margin-bottom: 10px;">Pilih Mata Pelajaran yang Diampu (Dapat memilih lebih dari satu) <span style="color: var(--danger);">*</span></label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
                        @forelse($subjects as $subject)
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer; margin-bottom: 0;">
                                <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" {{ (is_array(old('subject_ids')) && in_array($subject->id, old('subject_ids'))) || old('subject_id') == $subject->id ? 'checked' : '' }} style="width: auto;">
                                <span><span class="badge badge-info" style="font-size: 0.72rem;">{{ $subject->code }}</span> <strong>{{ $subject->name }}</strong></span>
                            </label>
                        @empty
                            <div style="color: var(--text-muted); font-size: 0.85rem; grid-column: 1/-1;">
                                Belum ada data mata pelajaran. Silakan <a href="{{ route('admin.subjects.create') }}">tambahkan mata pelajaran terlebih dahulu</a>.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="form-group">
                    <label style="margin-bottom: 10px;">Pilih Kelas yang Diajar (Dapat memilih lebih dari satu) <span style="color: var(--danger);">*</span></label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
                        @forelse($classrooms as $classroom)
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer; margin-bottom: 0;">
                                <input type="checkbox" name="classroom_ids[]" value="{{ $classroom->id }}" {{ is_array(old('classroom_ids')) && in_array($classroom->id, old('classroom_ids')) ? 'checked' : '' }} style="width: auto;">
                                <span><strong>{{ $classroom->name }}</strong> ({{ $classroom->academic_year }})</span>
                            </label>
                        @empty
                            <div style="color: var(--text-muted); font-size: 0.85rem; grid-column: 1/-1;">
                                Belum ada data kelas. Silakan <a href="{{ route('admin.classrooms.create') }}">tambahkan kelas terlebih dahulu</a>.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="academic_year">Tahun Ajaran <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="academic_year" name="academic_year" value="{{ old('academic_year', '2026/2027') }}" required placeholder="Contoh: 2026/2027">
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester <span style="color: var(--danger);">*</span></label>
                        <select id="semester" name="semester" required>
                            <option value="ganjil" {{ old('semester') === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('semester') === 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
