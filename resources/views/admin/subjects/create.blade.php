{{--
==============================================================================
Tujuan: Formulir tambah mata pelajaran baru.
Dipakai Oleh: Admin\SubjectController@create (Route /admin/subjects/create)
Dependensi: layouts.app
Fungsi Utama: Input kode dan nama mata pelajaran
Side Effect: Pengiriman data POST ke /admin/subjects
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')
@section('page-title', 'Tambah Mata Pelajaran Baru')

@section('content')
<div style="max-width: 560px;">
    <div class="card">
        <div class="card-header">
            <h3>Formulir Mata Pelajaran</h3>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="code">Kode Mata Pelajaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="Contoh: MAT-10 atau BIND-11">
                    <span class="form-text">Kode unik pengenal mata pelajaran.</span>
                </div>

                <div class="form-group">
                    <label for="name">Nama Mata Pelajaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Matematika Peminatan">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Mapel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
