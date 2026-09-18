{{--
==============================================================================
Tujuan: Halaman formulir pendaftaran siswa baru dan penempatan kelas.
Dipakai Oleh: Admin\StudentController@create (Route /admin/students/create)
Dependensi: layouts.app, Classroom
Fungsi Utama: Input biodata siswa dan pemilihan kelas terdaftar
Side Effect: Pengiriman data POST ke /admin/students
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Tambah Siswa Baru')
@section('page-title', 'Pendaftaran Siswa Baru')

@section('content')
<div style="max-width: 650px;">
    <div class="card">
        <div class="card-header">
            <h3>Formulir Data Siswa</h3>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="classroom_id">Penempatan Kelas <span style="color: var(--danger);">*</span></label>
                    <select id="classroom_id" name="classroom_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                                Kelas {{ $c->name }} ({{ $c->academic_year }})
                            </option>
                        @endforeach
                    </select>
                    <span class="form-text">
                        💡 Siswa yang didaftarkan ke kelas ini secara otomatis terdaftar dan wajib mengikuti seluruh mata pelajaran yang diajarkan di kelas tersebut.
                    </span>
                </div>

                <div class="form-group">
                    <label for="name">Nama Lengkap Siswa <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Fauzan">
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="nis">Nomor Induk Siswa (NIS) <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required placeholder="Contoh: 20261001">
                    </div>

                    <div class="form-group">
                        <label for="nisn">NISN (Opsional)</label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" placeholder="Contoh: 0081234567">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender">Jenis Kelamin <span style="color: var(--danger);">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Daftarkan Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
