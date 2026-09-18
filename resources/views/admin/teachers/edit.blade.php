{{--
==============================================================================
Tujuan: Halaman edit profil guru dan reset/ubah password akun guru oleh Admin.
Dipakai Oleh: Admin\TeacherController@edit (Route /admin/teachers/{id}/edit)
Dependensi: layouts.app, User model
Fungsi Utama: Edit biodata guru dan form terpisah ubah password
Side Effect: Pengiriman data PUT ke /admin/teachers/{id} dan /admin/teachers/{id}/password
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Edit Data Guru')
@section('page-title', 'Edit Akun Guru & Ubah Password')

@section('content')
<div style="max-width: 760px;">
    <!-- Form Edit Profil -->
    <div class="card">
        <div class="card-header">
            <h3>Perbarui Informasi Guru: {{ $teacher->name }}</h3>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary btn-sm">⬅ Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lengkap Guru <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="email">Alamat Email <span style="color: var(--danger);">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nip">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip', $teacher->nip) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Ubah Password Guru -->
    <div class="card" style="border-color: #fde68a;">
        <div class="card-header" style="background: #fffbeb;">
            <h3 style="color: #92400e;">🔑 Ubah Password Akun Guru</h3>
        </div>
        <div class="card-body">
            <p style="font-size: 0.85rem; color: #78350f; margin-bottom: 16px;">
                Sebagai Administrator, Anda dapat mengganti password guru ini secara langsung jika guru lupa kata sandi.
            </p>

            <form action="{{ route('admin.teachers.password', $teacher->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="new_password">Password Baru <span style="color: var(--danger);">*</span></label>
                        <input type="password" id="new_password" name="new_password" required placeholder="Minimal 6 karakter">
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru <span style="color: var(--danger);">*</span></label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Ulangi password baru">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="background: #d97706;">
                        🔐 Perbarui Password Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
