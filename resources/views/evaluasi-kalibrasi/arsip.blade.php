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
    .btn-tampilkan {
        background-color: #ffffff;
        border: 1px solid #1b3152;
        color: #1b3152;
    }
    .btn-tampilkan:hover,
    .btn-tampilkan:focus,
    .btn-tampilkan:active {
        background-color: #1b3152;
        border-color: #1b3152;
        color: #ffffff;
    }
    .btn-merah-custom {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
    }
    .btn-merah-custom:hover {
        background-color: #bb2d3b !important;
        border-color: #bb2d3b !important;
        color: #ffffff !important;
    }
    .btn-kuning-custom {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #212529 !important;
    }
    .btn-kuning-custom:hover {
        background-color: #e0a800 !important;
        border-color: #e0a800 !important;
        color: #212529 !important;
    }

    .evaluasi-header-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        align-items: center;
        gap: 0.5rem;
    }
    .evaluasi-header-actions-right {
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
        white-space: nowrap;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table-responsive > .table {
        min-width: 820px;
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
    .ikon-konfirmasi {
        width: 60px;
        height: 60px;
        font-size: 26px;
    }
    .ikon-konfirmasi.ikon-hapus {
        background-color: #fff3cd;
        color: #f0ad4e;
        border: 2px solid #ffe69c;
    }
    .ikon-konfirmasi.ikon-biasa {
        background-color: #eef2f8;
        color: #1b3152;
        border: 2px solid #d5deee;
    }
    @media (max-width: 575.98px) {
        .dashboard-container {
            padding: 2px 10px !important;
        }
        .evaluasi-header-actions,
        .evaluasi-header-actions-right {
            display: flex;
            width: 100%;
            justify-content: stretch;
            gap: 0.4rem;
        }
        .evaluasi-header-actions-right > .btn {
            flex: 1 1 calc(50% - 0.4rem);
            text-align: center;
            padding-top: 0.45rem !important;
            padding-bottom: 0.45rem !important;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    @php
        $adaFilter = request()->filled('q') || request()->filled('tanggal');
        $jumlahKolom = $adminAplikasi ? 8 : 7;
    @endphp

    <div class="mb-2">
        <h4 class="fw-bold mb-0">Arsip Evaluasi Kalibrasi</h4>
        <ol class="breadcrumb mb-0" style="font-size: 0.75rem;">
            <li class="breadcrumb-item"><a href="{{ route('evaluasi-kalibrasi.index') }}" class="text-decoration-none text-primary">Evaluasi Kalibrasi</a></li>
            <li class="breadcrumb-item text-muted active">Arsip</li>
        </ol>
    </div>

    <form id="formAksiMasal" action="{{ route('evaluasi-kalibrasi.hapus-terpilih') }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="evaluasi-header-actions mb-2">
            <div class="evaluasi-header-actions-right">
                @if($adminAplikasi && $evaluasi->total() > 0)
                    <button type="button" id="btnHapusTerpilih" class="btn btn-kuning-custom btn-sm py-1 px-2 shadow-sm" disabled>
                        <i class="fas fa-trash-alt me-1"></i> Hapus Terpilih<span id="jumlahTerpilih"></span>
                    </button>

                    <button type="button" class="btn btn-merah-custom btn-sm py-1 px-2 shadow-sm btn-konfirmasi-aksi" data-hapus="1" data-pesan="Semua data di dalam arsip akan dihapus secara permanen!" data-tombol="Ya, Hapus Semua!" data-action="{{ route('evaluasi-kalibrasi.hapus-semua') }}" data-method="DELETE">
                        <i class="fas fa-trash me-1"></i> Hapus Semua Arsip
                    </button>

                    <button type="button" class="btn btn-tampilkan btn-sm py-1 px-2 shadow-sm btn-konfirmasi-aksi" data-hapus="0" data-pesan="Semua data evaluasi di arsip akan ditampilkan kembali pada daftar aktif." data-tombol="Ya, Tampilkan Semua!" data-action="{{ route('evaluasi-kalibrasi.tampilkan-semua') }}" data-method="PATCH">
                        <i class="fas fa-eye me-1"></i> Tampilkan Semua
                    </button>
                @endif

                <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-corporate-blue btn-sm py-1 px-2 shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center bg-white shadow-sm rounded-3">
                <thead class="align-middle">
                    <tr>
                        @if($adminAplikasi)
                            <th style="width: 40px;">
                                <input type="checkbox" id="checkAll" class="form-check-input" style="cursor: pointer;">
                            </th>
                        @endif
                        <th style="width: 40px;">No</th>
                        <th class="sticky-alat text-start" style="width: 130px; min-width: 130px;">Alat</th>
                        <th style="width: 90px;">Tanggal</th>
                        <th style="width: 110px;">Keputusan</th>
                        <th style="width: 150px;">Dievaluasi Oleh</th>
                        <th style="width: 80px;">Laporan</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluasi as $index => $item)
                    <tr>
                        @if($adminAplikasi)
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $item->evaluasi_id }}" class="form-check-input select-item" style="cursor: pointer;">
                            </td>
                        @endif
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
                                @if($adminAplikasi)
                                    <button type="button" class="btn btn-tampilkan btn-sm py-1 px-2 shadow-sm btn-konfirmasi-aksi" data-hapus="0" data-pesan="Tampilkan kembali data evaluasi ini pada daftar aktif?" data-tombol="Ya, Tampilkan!" data-action="{{ route('evaluasi-kalibrasi.tampilkan', $item->evaluasi_id) }}" data-method="PATCH" title="Tampilkan">
                                        <i class="fas fa-eye me-1"></i> Tampilkan
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $jumlahKolom }}" class="text-center text-muted py-3">
                            {{ $adaFilter ? 'Data arsip tidak ditemukan untuk filter yang dipilih.' : 'Belum ada data di dalam arsip.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-2">
        {{ $evaluasi->links() }}
    </div>
</div>

@if($adminAplikasi)
<div class="modal fade modal-konfirmasi-evaluasi" id="modalKonfirmasiAksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content shadow text-center p-3">
            <div class="pt-2 pb-1">
                <div id="ikonKonfirmasi" class="ikon-konfirmasi ikon-hapus rounded-circle d-inline-flex align-items-center justify-content-center">
                    <i id="ikonKonfirmasiGlyph" class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0" id="konfirmasiAksiPesan" style="font-size: 0.85rem;"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <button type="button" id="btnKonfirmasiAksi" class="btn btn-danger btn-sm px-3 fw-semibold">Ya</button>
                <button type="button" class="btn btn-secondary btn-sm px-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif

@if($adminAplikasi)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.select-item');
        const btnHapusTerpilih = document.getElementById('btnHapusTerpilih');
        const formAksiMasal = document.getElementById('formAksiMasal');

        function toggleButtonHapusTerpilih() {
            const jumlah = Array.from(checkboxes).filter(cb => cb.checked).length;
            const labelJumlah = document.getElementById('jumlahTerpilih');

            if (btnHapusTerpilih) {
                btnHapusTerpilih.disabled = jumlah === 0;
            }
        
            if (labelJumlah) {
                labelJumlah.textContent = jumlah > 0 ? ' (' + jumlah + ')' : '';
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = checkAll.checked);
                toggleButtonHapusTerpilih();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (checkAll) {
                    checkAll.checked = Array.from(checkboxes).every(c => c.checked);
                }
                toggleButtonHapusTerpilih();
            });
        });

        let targetForm = null;
        let customAction = null;
        let customMethod = null;
        const modalEl = document.getElementById('modalKonfirmasiAksi');
        const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
        const ikon = document.getElementById('ikonKonfirmasi');
        const ikonGlyph = document.getElementById('ikonKonfirmasiGlyph');
        const btnKonfirmasi = document.getElementById('btnKonfirmasiAksi');

        function tampilkanModal(pesan, teksTombol, modeHapus) {
            document.getElementById('konfirmasiAksiPesan').textContent = pesan;
            btnKonfirmasi.textContent = teksTombol;

            ikon.classList.toggle('ikon-hapus', modeHapus);
            ikon.classList.toggle('ikon-biasa', !modeHapus);
            ikonGlyph.className = modeHapus ? 'fas fa-exclamation' : 'fas fa-question';

            btnKonfirmasi.classList.toggle('btn-danger', modeHapus);
            btnKonfirmasi.classList.toggle('btn-corporate-blue', !modeHapus);

            if (modal) modal.show();
        }

        document.querySelectorAll('.btn-konfirmasi-aksi').forEach(function (btn) {
            btn.addEventListener('click', function () {
                targetForm = this.closest('form');
                customAction = this.getAttribute('data-action');
                customMethod = this.getAttribute('data-method');

                tampilkanModal(
                    this.getAttribute('data-pesan'),
                    this.getAttribute('data-tombol'),
                    this.getAttribute('data-hapus') === '1'
                );
            });
        });

        if (btnHapusTerpilih) {
            btnHapusTerpilih.addEventListener('click', function () {
                targetForm = formAksiMasal;
                customAction = "{{ route('evaluasi-kalibrasi.hapus-terpilih') }}";
                customMethod = "DELETE";

                const jumlah = Array.from(checkboxes).filter(cb => cb.checked).length;

                tampilkanModal(jumlah + ' data evaluasi yang dipilih akan dihapus permanen!', 'Ya, Hapus!', true);
            });
        }

        if (btnKonfirmasi) {
            btnKonfirmasi.addEventListener('click', function () {
                if (!targetForm) return;

                if (customAction) targetForm.action = customAction;

                let methodInput = targetForm.querySelector('input[name="_method"]');
                if (customMethod) {
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        targetForm.appendChild(methodInput);
                    }
                    methodInput.value = customMethod;
                }

                targetForm.submit();
            });
        }
    });
</script>
@endpush
@endif
@endsection