{{--
==============================================================================
Tujuan: Halaman daftar mata pelajaran sekolah.
Dipakai Oleh: Admin\SubjectController@index (Route /admin/subjects)
Dependensi: layouts.app, Subject model
Fungsi Utama: Menampilkan tabel kode mapel, nama mapel, dan aksi edit/hapus
Side Effect: Menampilkan list data mapel
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Daftar Mata Pelajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Mata Pelajaran Aktif</h3>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">➕ Tambah Mata Pelajaran</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 150px;">Kode Mapel</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Total Kelas Diajarkan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                    <tr>
                        <td>
                            <span class="badge badge-info" style="font-size: 0.85rem;">{{ $subject->code }}</span>
                        </td>
                        <td>
                            <strong style="font-size: 0.95rem;">{{ $subject->name }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $subject->teaching_assignments_count }} Kelas</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-secondary btn-sm">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran {{ $subject->name }}?')" style="display: inline;">
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
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada mata pelajaran. Silakan tambahkan mata pelajaran baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subjects->hasPages())
        <div class="pagination-container">
            <div>Menampilkan {{ $subjects->firstItem() }} - {{ $subjects->lastItem() }} dari {{ $subjects->total() }} mapel</div>
            <div>{{ $subjects->links() }}</div>
        </div>
    @endif
</div>
@endsection
