@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Data Personil Laboratorium & Preparasi</h2>
                <a href="{{ route('sdm.kompetensi') }}" class="btn btn-info btn-sm text-white">Matriks Kompetensi & Sertifikasi</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No Induk</th>
                                <th>Nama Personil</th>
                                <th>Jabatan</th>
                                <th>Unit Kerja</th>
                                <th>CV</th>
                                <th>Status</th>
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
                                    @if($row->file_cv)
                                        <a href="{{ asset('storage/uploads/cv/'.$row->file_cv) }}" target="_blank" class="btn btn-sm btn-outline-primary">Unduh CV</a>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-success">Aktif</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data personil.</td>
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