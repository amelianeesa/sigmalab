@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Evaluasi Kalibrasi</h1>
        <a href="{{ route('evaluasi-kalibrasi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Input Evaluasi Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Alat</th>
                    <th>Keputusan</th>
                    <th>Dievaluasi Oleh</th>
                    <th>Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluasi as $item)
                <tr>
                    <td>{{ $item->tanggal_evaluasi->format('d/m/Y') }}</td>
                    <td>{{ $item->alat->nama_alat ?? '-' }} ({{ $item->alat->kode_alat ?? '-' }})</td>
                    <td>
                        @php
                            $keputusanLower = strtolower($item->keputusan);
                            $badge = 'secondary'; 
                            
                            if (str_contains($keputusanLower, 'layak') && !str_contains($keputusanLower, 'tidak')) {
                                $badge = 'success'; 
                            } elseif (str_contains($keputusanLower, 'tidak')) {
                                $badge = 'danger';  
                            } elseif ($keputusanLower == 'ya') {
                                $badge = 'info';    
                            }
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ strtoupper($item->keputusan) }}</span>
                    </td>
                    <td>{{ $item->evaluator->name ?? $item->evaluator->username ?? '-' }}</td>
                    <td>
                        @if($item->file_laporan)
                            <a href="{{ asset('storage/' . $item->file_laporan) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('evaluasi-kalibrasi.show', $item->evaluasi_id) }}" class="btn btn-sm btn-info text-white">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data evaluasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $evaluasi->links() }}
</div>
@endsection