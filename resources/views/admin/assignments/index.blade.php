{{--
==============================================================================
Tujuan: Halaman daftar penugasan guru ke mata pelajaran dan kelas.
Dipakai Oleh: Admin\TeachingAssignmentController@index (Route /admin/assignments)
Dependensi: layouts.app, TeachingAssignment model
Fungsi Utama: Menampilkan tabel penugasan aktif dan opsi pembatalan penugasan
Side Effect: Menampilkan list data teaching_assignments
==============================================================================
--}}
@extends('layouts.app')

@section('title', 'Penugasan Guru')
@section('page-title', 'Daftar Penugasan Guru ke Mapel & Kelas')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3>Penugasan Mengajar Aktif</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                Satu guru dapat mengampu satu mata pelajaran pada satu atau beberapa kelas sekaligus.
            </p>
        </div>
        <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary">➕ Daftarkan Guru ke Mapel</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Guru Pengampu</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Presensi & Nilai</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $assignment->teacher->name }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">
                                NIP: {{ $assignment->teacher->nip ?? '-' }} | {{ $assignment->teacher->email }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $assignment->subject->code }}</span>
                            <strong>{{ $assignment->subject->name }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-secondary" style="font-size: 0.85rem;">{{ $assignment->classroom->name }}</span>
                        </td>
                        <td>{{ $assignment->academic_year }}</td>
                        <td>
                            <span class="badge {{ $assignment->semester === 'ganjil' ? 'badge-warning' : 'badge-success' }}">
                                {{ ucfirst($assignment->semester) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">
                                <div>Presensi: <strong>{{ $assignment->attendances_count }}</strong> data</div>
                                <div>Nilai: <strong>{{ $assignment->grades_count }}</strong> siswa</div>
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Hapus penugasan {{ $assignment->teacher->name }} pada mapel {{ $assignment->subject->name }} ({{ $assignment->classroom->name }})?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada penugasan guru. Klik tombol "Daftarkan Guru ke Mapel" untuk membuat penugasan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($assignments->hasPages())
        <div class="pagination-container">
            <div>Menampilkan {{ $assignments->firstItem() }} - {{ $assignments->lastItem() }} dari {{ $assignments->total() }} penugasan</div>
            <div>{{ $assignments->links() }}</div>
        </div>
    @endif
</div>
@endsection
