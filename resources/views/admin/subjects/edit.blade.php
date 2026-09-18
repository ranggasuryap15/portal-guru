{{--
==============================================================================
Tujuan: Formulir edit data mata pelajaran.
Dipakai Oleh: Admin\SubjectController@edit (Route /admin/subjects/{id}/edit)
Dependensi: layouts.app, Subject model
Fungsi Utama: Edit kode dan nama mata pelajaran
Side Effect: Pengiriman data PUT ke /admin/subjects/{id}
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')
@section('page-title', 'Edit Mata Pelajaran: ' . $subject->name)

@section('content')
<div style="max-width: 560px;">
    <div class="card">
        <div class="card-header">
            <h3>Perbarui Mata Pelajaran</h3>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="code">Kode Mata Pelajaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="code" name="code" value="{{ old('code', $subject->code) }}" required>
                </div>

                <div class="form-group">
                    <label for="name">Nama Mata Pelajaran <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $subject->name) }}" required>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
