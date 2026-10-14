@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 20px !important;
    }
    .table th, .table td {
        padding: 6px 10px !important;
        vertical-align: middle !important;
        font-size: 0.76rem !important;
    }
    .table thead th {
        font-size: 0.78rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #dee2e6 !important;
    }
    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
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
    .btn-outline-primary-birdong {
        color: #1b3152 !important;
        background-color: transparent !important;
        border-color: #1b3152 !important;
    }
    .btn-outline-primary-birdong:hover,
    .btn-outline-primary-birdong:focus,
    .btn-outline-primary-birdong:active {
        color: #ffffff !important;
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
    }
    .btn-lihat-arsip {
        background-color: #eef2f8;
        border: 1px solid #c5d0e3;
        color: #1b3152;
    }
    .btn-lihat-arsip:hover,
    .btn-lihat-arsip:focus,
    .btn-lihat-arsip:active {
        background-color: #1b3152;
        border-color: #1b3152;
        color: #ffffff;
    }
    .btn-abu {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #ffffff;
    }
    .btn-abu:hover,
    .btn-abu:focus,
    .btn-abu:active {
        background-color: #565e64;
        border-color: #565e64;
        color: #ffffff;
    }

    .evaluasi-header-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .evaluasi-header-actions form {
        margin: 0;
    }
    .evaluasi-header-actions .btn {
        font-size: 0.73rem;
        font-weight: 600;
    }

    .filter-bar .form-control,
    .filter-bar .form-label {
        font-size: 0.76rem;
    }
    .filter-bar .form-label {
        margin-bottom: 2px;
        font-weight: 600;
        color: #1b3152;
    }
    .filter-bar .form-control:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .sticky-alat {
        position: sticky !important;
        left: 0 !important;
        width: 130px !important;
        min-width: 130px !important;
        z-index: 3;
        background-color: #ffffff !important;
    }

    thead th.sticky-alat {
        background-color: #1b3152 !important;
        z-index: 4;
    }

    .sticky-alat::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 4px;
        box-shadow: inset -3px 0 3px -2px rgba(0, 0, 0, 0.15);
    }

    .aksi-evaluasi {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .aksi-evaluasi form {
        margin: 0;
    }
    .aksi-evaluasi .btn {
        font-size: 0.7rem;
        font-weight: 600;
    }

    .modal-konfirmasi-evaluasi .modal-content {
        border: 0;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 2px 8px !important;
        }
        .evaluasi-header-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .evaluasi-header-actions > a,
        .evaluasi-header-actions > form,
        .evaluasi-header-actions .btn {
            width: 100%;
        }
        .evaluasi-header-actions > :last-child:nth-child(odd) {
            grid-column: 1 / -1;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    @php
        $allowedRoles = ['Koordinator Laboratorium', 'Analis Lab', 'Admin Aplikasi'];
        $userRoleName = Auth::user()->role->nama_role ?? '';
        $canInputEvaluasi = in_array($userRoleName, $allowedRoles);
        $isAdminAplikasi = $userRoleName === 'Admin Aplikasi';
        $adaFilter = request()->filled('q') || request()->filled('tanggal');
    @endphp

    <div class="mb-2">
        <h4 class="fw-bold mb-0">Evaluasi Kalibrasi</h4>
    </div>

    <div class="evaluasi-header-actions mb-2">
        @if($isAdminAplikasi)
            <form action="{{ route('evaluasi-kalibrasi.arsipkan-semua') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="button" class="btn btn-abu btn-sm py-1 px-2 shadow-sm btn-konfirmasi-aksi" data-pesan="Semua data evaluasi kalibrasi aktif akan dipindahkan ke arsip." data-tombol="Ya, Arsipkan Semua!">
                    <i class="fas fa-archive me-1"></i> Arsip Semua
                </button>
            </form>

            <a href="{{ route('evaluasi-kalibrasi.arsip') }}" class="btn btn-lihat-arsip btn-sm py-1 px-2 shadow-sm">
                <i class="fas fa-folder-open me-1"></i> Lihat Arsip
            </a>
        @endif

        @if($canInputEvaluasi)
            <a href="{{ route('evaluasi-kalibrasi.create') }}" class="btn btn-corporate-blue btn-sm py-1 px-2 shadow-sm">
                <i class="fas fa-plus me-1"></i> Input Evaluasi Baru
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('evaluasi-kalibrasi.index') }}" class="filter-bar card border-0 shadow-sm p-2 mb-2">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <label for="q" class="form-label">Cari Alat</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama atau kode alat...">
                </div>
            </div>

            <div class="col-12 col-md-3">
                <label for="tanggal" class="form-label">Tanggal Evaluasi</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm">
            </div>

            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-corporate-blue btn-sm flex-fill fw-semibold" style="font-size: 0.73rem;">
                    <i class="fas fa-filter me-1"></i> Terapkan
                </button>
                @if($adaFilter)
                    <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-outline-secondary btn-sm flex-fill fw-semibold" style="font-size: 0.73rem;">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center bg-white shadow-sm rounded-3">
            <thead class="align-middle">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th class="sticky-alat text-start" style="width: 130px; min-width: 130px;">Alat</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 110px;">Keputusan</th>
                    <th style="width: 150px;">Dievaluasi Oleh</th>
                    <th style="width: 80px;">Laporan</th>
                    <th style="width: {{ $isAdminAplikasi ? '140px' : '70px' }};">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluasi as $index => $item)
                <tr>
                    <td class="fw-semibold">
                        {{ $evaluasi->firstItem() + $index }}
                    </td>

                    <td class="sticky-alat text-start fw-bold bg-white text-truncate" style="max-width: 130px;">
                        <a href="{{ route('evaluasi-kalibrasi.show', $item->evaluasi_id) }}" class="text-decoration-none text-primary">
                            {{ $item->alat->nama_alat ?? '-' }}
                        </a>
                        <code class="text-dark fw-normal d-block" style="font-size: 0.68rem;">({{ $item->alat->kode_alat ?? '-' }})</code>
                    </td>

                    <td>{{ \Carbon\Carbon::parse($item->tanggal_evaluasi)->format('d/m/Y') }}</td>

                    <td>
                        @php
                            $keputusanLower = strtolower($item->keputusan);
                            $badge = 'secondary';

                            if (str_contains($keputusanLower, 'tidak')) {
                                $badge = 'danger';
                            } elseif (str_contains($keputusanLower, 'faktor') || str_contains($keputusanLower, 'koreksi') || str_contains($keputusanLower, 'penambahan')) {
                                $badge = 'warning text-dark';
                            } elseif (str_contains($keputusanLower, 'layak')) {
                                $badge = 'success';
                            }
                        @endphp
                        <span class="badge bg-{{ $badge }}" style="font-size: 0.55rem; padding: 0.3em 0.4em; white-space: normal; display: inline-block; max-width: 100px; word-break: break-word; line-height: 1.2;">
                            {{ strtoupper(str_replace('_', ' ', $item->keputusan)) }}
                        </span>
                    </td>

                    <td>{{ $item->evaluator->name ?? $item->evaluator->username ?? '-' }}</td>

                    <td>
                        @if($item->file_laporan)
                            <a href="{{ asset('storage/' . $item->file_laporan) }}" target="_blank" class="btn btn-outline-primary-birdong btn-sm py-0.5 px-1.5" style="font-size: 0.68rem;" title="Lihat Laporan">
                                <i class="fas fa-file-alt"></i> Lihat
                            </a>
                        @else
                            -
                        @endif
                    </td>

                    <td class="text-nowrap">
                        <div class="aksi-evaluasi">
                            <a href="{{ route('evaluasi-kalibrasi.show', $item->evaluasi_id) }}" class="btn btn-corporate-blue btn-sm py-1 px-2 shadow-sm" title="Detail">
                                Detail
                            </a>
                            @if($isAdminAplikasi)
                                <form action="{{ route('evaluasi-kalibrasi.arsipkan', $item->evaluasi_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="btn btn-abu btn-sm py-1 px-2 shadow-sm btn-konfirmasi-aksi" data-pesan="Pindahkan data evaluasi ini ke arsip?" data-tombol="Ya, Arsipkan!" title="Arsipkan">
                                        <i class="fas fa-archive me-1"></i> Arsip
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">
                        {{ $adaFilter ? 'Data evaluasi tidak ditemukan untuk filter yang dipilih.' : 'Belum ada data evaluasi.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $evaluasi->links() }}
    </div>
</div>

@if($isAdminAplikasi)
<div class="modal fade modal-konfirmasi-evaluasi" id="modalKonfirmasiAksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content shadow text-center p-3">
            <div class="pt-2 pb-1">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #eef2f8; color: #1b3152; font-size: 26px; border: 2px solid #d5deee;">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0" id="konfirmasiAksiPesan" style="font-size: 0.85rem;"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <button type="button" id="btnKonfirmasiAksi" class="btn btn-corporate-blue btn-sm px-3 fw-semibold">Ya</button>
                <button type="button" class="btn btn-secondary btn-sm px-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let formKonfirmasi = null;
        const modalEl = document.getElementById('modalKonfirmasiAksi');
        const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

        document.querySelectorAll('.btn-konfirmasi-aksi').forEach(function (btn) {
            btn.addEventListener('click', function () {
                formKonfirmasi = this.closest('form');
                document.getElementById('konfirmasiAksiPesan').textContent = this.getAttribute('data-pesan');
                document.getElementById('btnKonfirmasiAksi').textContent = this.getAttribute('data-tombol');
                if (modal) modal.show();
            });
        });

        const btnKonfirmasi = document.getElementById('btnKonfirmasiAksi');
        if (btnKonfirmasi) {
            btnKonfirmasi.addEventListener('click', function () {
                if (formKonfirmasi) formKonfirmasi.submit();
            });
        }
    });
</script>
@endpush
@endif
@endsection