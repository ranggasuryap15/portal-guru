{{--
==============================================================================
Tujuan: Halaman daftar kelas dengan jumlah siswa dan mapel terdaftar.
Dipakai Oleh: Admin\ClassroomController@index (Route /admin/classrooms)
Dependensi: layouts.app, Classroom model
Fungsi Utama: Menampilkan tabel kelas, tahun ajaran, aksi edit dan hapus
Side Effect: Menampilkan list data kelas
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Kelola Kelas')
@section('page-title', 'Daftar Kelas Sekolah')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Kelas & Tahun Ajaran</h3>
        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">➕ Tambah Kelas Baru</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Jumlah Siswa</th>
                    <th>Jumlah Mapel Diajarkan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $classroom)
                    <tr>
                        <td>
                            <strong style="font-size: 1rem;">{{ $classroom->name }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $classroom->academic_year }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.students.index', ['classroom_id' => $classroom->id]) }}" class="badge badge-info" style="text-decoration: none;">
                                👥 {{ $classroom->students_count }} Siswa
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-success">{{ $classroom->teaching_assignments_count }} Mapel</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="btn btn-secondary btn-sm">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" onsubmit="return confirm('Hapus kelas {{ $classroom->name }}? Seluruh siswa dan penugasan di kelas ini akan ikut terhapus.')" style="display: inline;">
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
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada data kelas. Silakan tambahkan kelas baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($classrooms->hasPages())
        <div class="pagination-container">
            <div>Menampilkan {{ $classrooms->firstItem() }} - {{ $classrooms->lastItem() }} dari {{ $classrooms->total() }} kelas</div>
            <div>{{ $classrooms->links() }}</div>
        </div>
    @endif
</div>
@endsection
