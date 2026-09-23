@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Evaluasi Kalibrasi</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('evaluasi-kalibrasi.edit', $evaluasi->evaluasi_id) }}" class="btn btn-warning text-dark">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <form action="{{ route('evaluasi-kalibrasi.destroy', $evaluasi->evaluasi_id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data evaluasi ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <style>th { width: 25%; }</style>
                    <th>Tanggal Evaluasi</th>
                    <td>: {{ $evaluasi->tanggal_evaluasi->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Alat</th>
                    <td>: {{ $evaluasi->alat->nama_alat ?? '-' }} ({{ $evaluasi->alat->kode_alat ?? '-' }})</td>
                </tr>
                <tr>
                    <th>Keputusan</th>
                    <td>: 
                        @php
                            $keputusanLower = strtolower($evaluasi->keputusan);
                            $badge = 'secondary';
                            if (str_contains($keputusanLower, 'layak') && !str_contains($keputusanLower, 'tidak')) {
                                $badge = 'success';
                            } elseif (str_contains($keputusanLower, 'tidak')) {
                                $badge = 'danger';
                            }
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ strtoupper($evaluasi->keputusan) }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Dievaluasi Oleh</th>
                    <td>: {{ $evaluasi->evaluator->name ?? $evaluasi->evaluator->username ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Komentar</th>
                    <td>: {{ $evaluasi->catatan_spesifikasi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Laporan Evaluasi</th>
                    <td>: 
                        @if($evaluasi->file_laporan)
                            <a href="{{ asset('storage/' . $evaluasi->file_laporan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen Laporan
                            </a>
                        @else
                            <span class="text-muted">Tidak ada file lampiran</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection