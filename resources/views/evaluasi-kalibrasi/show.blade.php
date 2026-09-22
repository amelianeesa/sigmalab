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
    /* Custom Styling SweetAlert2 agar pas dan sesuai referensi */
    .swal2-popup.swal2-compact {
        width: 420px !important;
        padding: 1.5rem !important;
        border-radius: 0.5rem !important;
    }
    .swal2-popup.swal2-compact .swal2-title {
        font-size: 1.35rem !important;
        color: #333333 !important;
        font-weight: 600 !important;
        margin-top: 0.5rem !important;
    }
    .swal2-popup.swal2-compact .swal2-html-container {
        font-size: 0.9rem !important;
        color: #555555 !important;
        margin: 0.5rem 0 1.2rem 0 !important;
    }
    .swal2-popup.swal2-compact .swal2-icon {
        transform: scale(0.85);
        margin: 0.5em auto 0.2em !important;
    }
    .swal2-popup.swal2-compact .swal2-styled {
        font-size: 0.85rem !important;
        padding: 0.5rem 1.2rem !important;
        border-radius: 0.3rem !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-0 pb-3 px-4" style="max-width: 950px;">
    
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1">
        <div>
        </div>
        <div class="d-flex align-items-center gap-1">
            <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-outline-secondary btn-sm fw-bold py-1 px-2 shadow-sm" style="font-size: 11px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('evaluasi-kalibrasi.edit', $evaluasi->evaluasi_id) }}" class="btn btn-sm text-nowrap py-1 px-2 shadow-sm d-flex align-items-center text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; font-size: 11px; gap: 5px;">
                <i class="fas fa-edit text-white"></i> <span class="text-white">Edit</span>
            </a>
            <form action="{{ route('evaluasi-kalibrasi.destroy', $evaluasi->evaluasi_id) }}" method="POST" class="d-inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger btn-sm fw-bold py-1 px-2 shadow-sm delete-btn" style="font-size: 11px;">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </form>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteBtn = document.querySelector('.delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data evaluasi kalibrasi ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    // Tombol Ya, Hapus! di kiri (merah), Batal di kanan (abu-abu)
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, // Membuat posisi tombol "Ya, Hapus!" berada di kiri
                    customClass: {
                        popup: 'swal2-compact'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endpush