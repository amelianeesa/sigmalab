@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Data Personil Laboratorium & Preparasi</h2>
                <div>
                    <a href="{{ route('sdm.create') }}" class="btn btn-primary btn-sm">+ Tambah Personil</a>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No Induk</th>
                                <th>Nama Personil</th>
                                <th>Jabatan</th>
                                <th>Unit Kerja</th>
                                <th>Sertifikasi</th>
                                <th>CV</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($personil as $row)
                            <tr>
                                <td>{{ $row->no_induk }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->jabatan }}</td>
                                <td>{{ $row->unit_kerja }}</td>
                                <td>
                                    <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}" class="btn btn-sm btn-info text-white">Lihat Sertifikasi</a>
                                </td>
                                <td>
                                    @if($row->file_cv)
                                        <a href="{{ route('sdm.cv', $row->personil_id) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat CV</a>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('sdm.edit', $row->personil_id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('sdm.destroy', $row->personil_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus/menonaktifkan data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data personil.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection