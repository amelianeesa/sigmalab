@extends('layouts.app')

@push('styles')
<style>
    .container-fluid {
        padding-top: 2px !important;
    }
    .card-body {
        padding: 0.85rem 1.1rem !important;
    }
    .table-detail-evaluasi td, .table-detail-evaluasi th {
        padding: 6px 10px !important;
        font-size: 0.78rem !important;
        vertical-align: middle;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
        border: 1px solid #e3e6f0;
    }
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, 
    .btn-corporate-blue:focus, 
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-0 pb-3 px-4" style="max-width: 950px;">
    
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1">
        <div></div>
        <div class="d-flex align-items-center gap-1">
            <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-outline-secondary btn-sm fw-bold py-1 px-2 shadow-sm" style="font-size: 11px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('evaluasi-kalibrasi.edit', $evaluasi->evaluasi_id) }}" class="btn btn-sm text-nowrap py-1 px-2 shadow-sm d-flex align-items-center text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; font-size: 11px; gap: 5px;">
                <i class="fas fa-edit text-white"></i> <span class="text-white">Edit</span>
            </a>
            
            <!-- Tombol Trigger Modal Bootstrap -->
            <button type="button" class="btn btn-danger btn-sm fw-bold py-1 px-2 shadow-sm" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#modalHapusEvaluasi">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
        </div>
    </div>

    <div class="card shadow-sm card-shadow-custom bg-white">
        <div class="card-header text-white fw-bold py-2 px-3 d-flex align-items-center" style="background-color: #1b3152; font-size: 0.85rem;">
            Informasi Detail Evaluasi Kalibrasi
        </div>
        <div class="card-body">
            
            <table class="table table-borderless mb-0 table-detail-evaluasi">
                <tr>
                    <td style="width: 180px;" class="fw-bold text-secondary">Tanggal Evaluasi</td>
                    <td>: <span class="fw-bold text-dark">{{ $evaluasi->tanggal_evaluasi->format('d/m/Y') }}</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-secondary">Alat</td>
                    <td>: <span class="fw-bold text-dark">{{ $evaluasi->alat->nama_alat ?? '-' }}</span> (<code class="text-primary">{{ $evaluasi->alat->kode_alat ?? '-' }}</code>)</td>
                </tr>
                <tr>
                    <td class="fw-bold text-secondary">Keputusan</td>
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
                        <span class="badge bg-{{ $badge }}" style="font-size: 0.68rem; padding: 0.25rem 0.5rem;">{{ strtoupper($evaluasi->keputusan) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-secondary">Dievaluasi Oleh</td>
                    <td>: <span class="text-dark fw-semibold">{{ $evaluasi->evaluator->name ?? $evaluasi->evaluator->username ?? '-' }}</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-secondary">Komentar</td>
                    <td>: <span class="text-dark">{{ $evaluasi->catatan_spesifikasi ?? '-' }}</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-secondary">Laporan Evaluasi</td>
                    <td>: 
                        @if($evaluasi->file_laporan)
                            <a href="{{ asset('storage/' . $evaluasi->file_laporan) }}" target="_blank" class="btn btn-sm text-nowrap py-1 px-2 shadow-sm d-inline-flex align-items-center text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; font-size: 11px; gap: 5px;">
                                <i class="fas fa-file-pdf text-white"></i> <span class="text-white">Lihat Dokumen</span>
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Bootstrap -->
<div class="modal fade" id="modalHapusEvaluasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content border-0 shadow text-center p-3" style="font-size: 0.78rem; border-radius: 8px;">
            <div class="pt-2 pb-1">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #fff8e6; color: #f0ad4e; font-size: 22px; border: 2px solid #ffeeba;">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0" style="font-size: 0.72rem;">Data evaluasi kalibrasi ini akan dihapus permanen!</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <form action="{{ route('evaluasi-kalibrasi.destroy', $evaluasi->evaluasi_id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm py-1 px-3 fw-semibold rounded-2" style="font-size: 0.73rem;">Ya, Hapus!</button>
                </form>
                <button type="button" class="btn btn-secondary btn-sm py-1 px-3 fw-semibold rounded-2" data-bs-dismiss="modal" style="font-size: 0.73rem;">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection