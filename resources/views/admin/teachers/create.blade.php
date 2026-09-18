{{--
==============================================================================
Tujuan: Halaman formulir pendaftaran akun guru baru.
Dipakai Oleh: Admin\TeacherController@create (Route /admin/teachers/create)
Dependensi: layouts.app
Fungsi Utama: Input nama, email, NIP, no telp, password, konfirmasi password
Side Effect: Pengiriman data POST ke /admin/teachers
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Tambah Akun Guru')
@section('page-title', 'Pendaftaran Akun Guru Baru')

@section('content')
<div style="max-width: 680px;">
    <div class="card">
        <div class="card-header">
            <h3>Formulir Data Guru</h3>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.teachers.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap Guru <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso, S.Pd.">
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="email">Alamat Email <span style="color: var(--danger);">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="guru@sekolah.sch.id">
                        <span class="form-text">Digunakan untuk login ke sistem.</span>
                    </div>

                    <div class="form-group">
                        <label for="nip">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip') }}" placeholder="198501012010011001">
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="08123456789">
                </div>

                <div class="grid grid-2" style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px dashed var(--border-color); margin-top: 10px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password">Password Awal <span style="color: var(--danger);">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password_confirmation">Konfirmasi Password <span style="color: var(--danger);">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Akun Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
