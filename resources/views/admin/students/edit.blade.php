{{--
==============================================================================
Tujuan: Halaman formulir edit biodata siswa dan pemindahan kelas.
Dipakai Oleh: Admin\StudentController@edit (Route /admin/students/{id}/edit)
Dependensi: layouts.app, Student, Classroom
Fungsi Utama: Edit informasi siswa dan kelas
Side Effect: Pengiriman data PUT ke /admin/students/{id}
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Edit Data Siswa')
@section('page-title', 'Edit Data Siswa: ' . $student->name)

@section('content')
<div style="max-width: 650px;">
    <div class="card">
        <div class="card-header">
            <h3>Perbarui Data Siswa</h3>
            <a href="{{ route('admin.students.index', ['classroom_id' => $student->classroom_id]) }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="classroom_id">Penempatan Kelas <span style="color: var(--danger);">*</span></label>
                    <select id="classroom_id" name="classroom_id" required>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}" {{ old('classroom_id', $student->classroom_id) == $c->id ? 'selected' : '' }}>
                                Kelas {{ $c->name }} ({{ $c->academic_year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Nama Lengkap Siswa <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="nis">Nomor Induk Siswa (NIS) <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="nis" name="nis" value="{{ old('nis', $student->nis) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nisn">NISN (Opsional)</label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender">Jenis Kelamin <span style="color: var(--danger);">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="L" {{ old('gender', $student->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $student->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.students.index', ['classroom_id' => $student->classroom_id]) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
