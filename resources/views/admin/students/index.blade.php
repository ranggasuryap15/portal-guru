{{--
==============================================================================
Tujuan: Halaman daftar siswa dengan filter kelas dan pencarian.
Dipakai Oleh: Admin\StudentController@index (Route /admin/students)
Dependensi: layouts.app, Student, Classroom
Fungsi Utama: Menampilkan tabel data siswa, filter per kelas, pencarian NIS/nama, aksi CRUD
Side Effect: Menampilkan list siswa
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Kelola Siswa')
@section('page-title', 'Daftar Siswa Sekolah')

@section('content')
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 12px;">
        <form action="{{ route('admin.students.index') }}" method="GET" style="display: flex; gap: 10px; flex-grow: 1; max-width: 540px;">
            <select name="classroom_id" style="width: auto; min-width: 170px;" onchange="this.form.submit()">
                <option value="">-- Semua Kelas --</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ $classroomId == $c->id ? 'selected' : '' }}>
                        Kelas {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIS..." style="margin: 0;">
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if($search || $classroomId)
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" title="Reset filter">✖</a>
            @endif
        </form>

        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">➕ Tambah Siswa Baru</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 120px;">NIS</th>
                    <th>Nama Lengkap Siswa</th>
                    <th>L/P</th>
                    <th>Kelas Terdaftar</th>
                    <th>Tahun Ajaran</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>
                            <strong>{{ $student->nis }}</strong>
                            @if($student->nisn)
                                <div style="font-size: 0.75rem; color: var(--text-muted);">NISN: {{ $student->nisn }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.95rem;">{{ $student->name }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $student->gender === 'L' ? 'badge-info' : 'badge-warning' }}">
                                {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-secondary" style="font-size: 0.85rem;">{{ $student->classroom->name }}</span>
                        </td>
                        <td>{{ $student->classroom->academic_year }}</td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-secondary btn-sm">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa {{ $student->name }}?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Tidak ada data siswa yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
        <div class="pagination-container">
            <div>Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} dari {{ $students->total() }} siswa</div>
            <div>{{ $students->links() }}</div>
        </div>
    @endif
</div>
@endsection
