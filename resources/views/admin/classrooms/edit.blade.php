{{--
==============================================================================
Tujuan: Halaman formulir edit kelas.
Dipakai Oleh: Admin\ClassroomController@edit (Route /admin/classrooms/{id}/edit)
Dependensi: layouts.app, Classroom model
Fungsi Utama: Edit nama kelas dan tahun ajaran
Side Effect: Pengiriman data PUT ke /admin/classrooms/{id}
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Data Kelas: ' . $classroom->name)

@section('content')
<div style="max-width: 560px;">
    <div class="card">
        <div class="card-header">
            <h3>Perbarui Kelas</h3>
            <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Kelas <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $classroom->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="academic_year">Tahun Ajaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="academic_year" name="academic_year" value="{{ old('academic_year', $classroom->academic_year) }}" required>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
