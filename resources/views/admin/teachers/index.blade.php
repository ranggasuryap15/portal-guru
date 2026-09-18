{{--
==============================================================================
Tujuan: Halaman daftar akun Guru dengan pencarian, pagination, dan aksi edit/reset password.
Dipakai Oleh: Admin\TeacherController@index (Route /admin/teachers)
Dependensi: layouts.app, User model
Fungsi Utama: Menampilkan tabel akun guru, search NIP/Nama, tombol aksi
Side Effect: Menampilkan list user role guru
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Kelola Akun Guru')
@section('page-title', 'Daftar Akun Guru')

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.teachers.index') }}" method="GET" style="display: flex; gap: 10px; max-width: 400px; width: 100%;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIP, atau email..." style="margin: 0;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if($search)
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary" title="Reset filter">✖</a>
            @endif
        </form>

        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">➕ Tambah Guru Baru</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Total Penugasan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td>
                            <strong>{{ $teacher->nip ?? '-' }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $teacher->name }}</div>
                        </td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->phone ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $teacher->teaching_assignments_count }} Mapel/Kelas</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-secondary btn-sm" title="Edit Profil & Ubah Password">
                                    ✏️ Edit / Password
                                </a>
                                <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun guru {{ $teacher->name }}?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Tidak ada data akun guru yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($teachers->hasPages())
        <div class="pagination-container">
            <div>Menampilkan {{ $teachers->firstItem() }} - {{ $teachers->lastItem() }} dari {{ $teachers->total() }} data</div>
            <div>{{ $teachers->links() }}</div>
        </div>
    @endif
</div>
@endsection
