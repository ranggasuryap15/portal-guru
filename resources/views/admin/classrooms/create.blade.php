{{--
==============================================================================
Tujuan: Halaman formulir tambah kelas baru.
Dipakai Oleh: Admin\ClassroomController@create (Route /admin/classrooms/create)
Dependensi: layouts.app
Fungsi Utama: Input nama kelas dan tahun ajaran
Side Effect: Pengiriman data POST ke /admin/classrooms
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Tambah Kelas Baru')
@section('page-title', 'Tambah Kelas Baru')

@section('content')
<div style="max-width: 560px;">
    <div class="card">
        <div class="card-header">
            <h3>Formulir Kelas</h3>
            <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.classrooms.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Kelas <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: X-IPA-1 atau XII-TKJ-2">
                </div>

                <div class="form-group">
                    <label for="academic_year">Tahun Ajaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="academic_year" name="academic_year" value="{{ old('academic_year', '2026/2027') }}" required placeholder="Contoh: 2026/2027">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
