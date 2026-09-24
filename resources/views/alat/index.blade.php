@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 4px 20px !important;
    }

    .dashboard-card {
        padding: 12px !important;
    }

    .card-body {
        padding: 10px !important;
    }

    .table th, .table td {
        padding: 8px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    
    .table thead th {
        font-size: 0.75rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
    }

    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }
    .table thead.table-dark th, 
    .table thead th {
        border-color: #ffffff !important;
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

<div class="container-fluid dashboard-container" style="font-size: 0.82rem;">
    @php
        $alatWarningCount = 0;
        foreach($alat as $item) {
            $kalibrasi = $item->riwayatKalibrasi->sortByDesc('tgl_kalibrasi')->first();
            if ($kalibrasi && $kalibrasi->tgl_akhir) {
                $tglAkhir = \Carbon\Carbon::parse($kalibrasi->tgl_akhir);
                $sisaHari = \Carbon\Carbon::now()->startOfDay()->diffInDays($tglAkhir, false);
                if ($sisaHari <= 180) {
                    $alatWarningCount++;
                }
            }
        }
    @endphp

    @if($alatWarningCount > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm py-1 px-2.5 mb-2 d-flex align-items-center justify-content-between" role="alert" style="font-size: 0.8rem;">
            <div class="pe-2">
                <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian!</strong> Terdapat <strong>{{ $alatWarningCount }} alat</strong> yang masa kalibrasinya sudah kadaluwarsa atau akan segera berakhir (dalam 180 hari ke depan). Mohon segera jadwalkan kalibrasi ulang.
            </div>
            <button type="button" class="btn-close m-0 p-2" data-bs-dismiss="modal" aria-label="Close" style="transform: scale(0.75); position: absolute; right: 15px; top: 50%; transform: translateY(-50%) scale(0.8);"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0 text" style="font-size: 1.1rem;">
             Data Alat & Informasi Kalibrasi
        </h5>
        
        <div class="d-flex align-items-center gap-2">
            @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
            <a href="{{ route('alat.create') }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;"><i class="fas fa-plus me-1"></i> Tambah Alat</a>
            @endif
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('alat.index') }}" method="GET" id="filterForm" class="row g-2 mb-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" id="searchInput" class="form-control form-control-sm py-1.5" placeholder="Cari Nama Alat, Kode, atau Merk..." value="{{ $search ?? '' }}" autocomplete="off" style="font-size: 0.82rem;">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="filter_status" id="filterStatus" class="form-select form-select-sm py-1.5" style="font-size: 0.82rem;">
                        <option value="">-- Filter Status Kalibrasi --</option>
                        <option value="aktif" {{ (isset($filterStatus) && $filterStatus == 'aktif') ? 'selected' : '' }}>Aktif (> 180 Hari)</option>
                        <option value="segera" {{ (isset($filterStatus) && $filterStatus == 'segera') ? 'selected' : '' }}>Segera Berakhir (&le; 180 Hari)</option>
                        <option value="kedaluarsa" {{ (isset($filterStatus) && $filterStatus == 'kedaluarsa') ? 'selected' : '' }}>Kadalwuarsa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="filter_kondisi" id="filterKondisi" class="form-select form-select-sm py-1.5" style="font-size: 0.82rem;">
                        <option value="">-- Filter Kondisi Alat --</option>
                        <option value="baik" {{ (isset($filterKondisi) && $filterKondisi == 'baik') ? 'selected' : '' }}>Baik</option>
                        <option value="perbaikan" {{ (isset($filterKondisi) && $filterKondisi == 'perbaikan') ? 'selected' : '' }}>Perbaikan</option>
                        <option value="rusak" {{ (isset($filterKondisi) && $filterKondisi == 'rusak') ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-1.5" title="Reset"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>

            <div class="table-responsive" id="table-container">
                <table class="table table-bordered table-striped align-middle text-center" style="font-size: 0.78rem;">
                    <thead class="align-middle">
                        <tr>
                            <th rowspan="2" style="width: 45px;">No.</th>
                            <th rowspan="2" style="width: 65px;">QR Code</th>
                            <th rowspan="2">Nama Alat</th>
                            <th rowspan="2">CODE</th>
                            <th rowspan="2" style="min-width: 100px;">No. Inventaris</th>
                            <th colspan="5">Spesifikasi</th>
                            <th rowspan="2">Kondisi Alat</th>
                            <th rowspan="2">Status Alat</th>
                            <th rowspan="2" style="min-width: 100px;">No. Sertifikat/<br>Perijinan</th>
                            <th rowspan="2">Interval Kalibrasi</th>
                            <th colspan="2">Periode Kalibrasi/<br>Perijinan</th>
                            <th rowspan="2" style="min-width: 100px;">Unit Kerja Pemilik</th>
                            <th rowspan="2" style="min-width: 100px;">Lembaga Kalibrasi</th>
                            <th colspan="4">Kalibrasi</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Merk / Type</th>
                            <th>Serial Number</th>
                            <th>Warna</th>
                            <th>Ukuran</th>
                            <th>Unit Pemilik</th>
                            <th style="min-width: 95px;">Tgl Kalibrasi</th>
                            <th style="min-width: 105px;">Masa Berakhir<br>Kalibrasi</th>
                            <th style="min-width: 95px;">Jenis Kalibrasi</th>
                            <th style="min-width: 75px;">Range / Kapasitas</th>
                            <th style="min-width: 90px;">Faktor Koreksi</th>
                            <th style="min-width: 75px;">Signifikan (Ya/Tidak)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alat as $index => $item)
                        @php
                            $kalibrasiTerakhir = $item->riwayatKalibrasi->sortByDesc('tgl_kalibrasi')->first();
                            $jenisKalibrasi = optional($kalibrasiTerakhir)->jenis_kalibrasi;
                            $signifikan = optional($kalibrasiTerakhir)->signifikan;

                            $statusKalibrasiBadge = '';
                            if ($kalibrasiTerakhir && $kalibrasiTerakhir->tgl_akhir) {
                                $tglAkhir = \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_akhir);
                                $sekarang = \Carbon\Carbon::now()->startOfDay();
                                $sisaHari = $sekarang->diffInDays($tglAkhir, false);

                                if ($sisaHari < 0) {
                                    $statusKalibrasiBadge = '<span class="badge bg-danger mt-1 d-block px-2 py-1" style="font-size: 0.65rem;"><i class="fas fa-times-circle"></i> Kedaluarsa</span>';
                                } elseif ($sisaHari <= 180) {
                                    $statusKalibrasiBadge = '<span class="badge bg-warning text-dark mt-1 d-block px-2 py-1" style="font-size: 0.65rem;" title="Sisa ' . $sisaHari . ' hari lagi"><i class="fas fa-clock"></i> Segera Berakhir (' . $sisaHari . 'h)</span>';
                                }
                            }

                            $qrData = route('alat.public-scan', $item->kode_alat);

                            $qrSvgCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(40)->generate($qrData);
                            $qrSvgLarge = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($qrData);
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="p-1 bg-white d-inline-block shadow-sm rounded qr-thumbnail"
                                     style="cursor: pointer;"
                                     title="Klik untuk memperbesar"
                                     data-bs-toggle="modal"
                                     data-bs-target="#qrModal"
                                     data-qrsvg="{!! htmlspecialchars($qrSvgLarge, ENT_QUOTES, 'UTF-8') !!}"
                                     data-alatanama="{{ $item->nama_alat }}"
                                     data-alatkode="{{ $item->kode_alat }}">
                                    {!! $qrSvgCode !!}
                                </div>
                            </td>
                            <td class="fw-bold text-start">
                                <a href="{{ route('alat.input-kalibrasi', $item->alat_id) }}" class="text-decoration-none text-primary" title="Buka Halaman Kalibrasi">
                                    {{ $item->nama_alat }} <i class="fas fa-external-link-alt ms-1" style="font-size: 0.6rem;"></i>
                                </a>
                            </td>
                            <td><code class="fw-bold text-dark">{{ $item->kode_alat }}</code></td>
                            <td>{{ $item->no_inventaris ?? '-' }}</td>
                            <td class="text-center">{{ $item->merk_tipe ?? '-' }}</td>
                            <td>{{ $item->no_seri ?? '-' }}</td>
                            <td>{{ $item->warna ?? '-' }}</td>
                            <td>{{ $item->ukuran ?? '-' }}</td>
                            <td>{{ $item->unit_kerja_pemilik ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->kondisi_barang == 'baik' ? 'success' : 'danger' }}" style="font-size: 0.7rem;">
                                    {{ ucfirst($item->kondisi_barang) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->status_barang == 'terpakai' ? 'primary' : 'secondary' }}" style="font-size: 0.7rem;">
                                    {{ ucfirst($item->status_barang) }}
                                </span>
                            </td>
                            <td>
                                {{ $kalibrasiTerakhir->no_sertifikat ?? '-' }}
                                @if(!empty($kalibrasiTerakhir->file_sertifikat))
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/' . $kalibrasiTerakhir->file_sertifikat) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0.5 px-1.5" style="font-size: 0.65rem;" title="Lihat Sertifikat">
                                            <i class="fas fa-file-alt"></i> Lihat
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $kalibrasiTerakhir->interval_kalibrasi ?? '-' }}</td>
                            <td>{{ $kalibrasiTerakhir?->tgl_kalibrasi ? \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_kalibrasi)->format('d-m-Y') : '-' }}</td>
                            <td>
                                {{ $kalibrasiTerakhir?->tgl_akhir ? \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_akhir)->format('d-m-Y') : '-' }}
                                {!! $statusKalibrasiBadge !!}
                            </td>
                            <td>{{ $item->unit_kerja_pemilik ?? '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->lembaga_kalibrasi ?? '-' }}</td>
                            <td>{{ $jenisKalibrasi ? ucfirst($jenisKalibrasi) : '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->range_kapasitas ?? '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->faktor_koreksi ?? '-' }}</td>
                            <td>{{ ucfirst($signifikan ?? '-') }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('alat.show', $item->alat_id) }}" class="btn btn-corporate-blue btn-sm py-1 px-2 shadow-sm" style="font-size: 0.75rem;" title="Detail Kerusakan & Perbaikan"><i class="fas fa-tools"></i></a>
                                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                                    <a href="{{ route('alat.edit', $item->alat_id) }}" class="btn btn-warning btn-sm py-1 px-2 text-dark shadow-sm" style="font-size: 0.75rem;" title="Edit"><i class="fas fa-edit"></i></a>

                                    <button type="button" class="btn btn-danger btn-sm py-1 px-2 shadow-sm" style="font-size: 0.75rem;" title="Hapus" data-bs-toggle="modal" data-bs-target="#modalHapusAlat{{ $item->alat_id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <div class="modal fade" id="modalHapusAlat{{ $item->alat_id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
                                            <div class="modal-content border-0 shadow text-center p-3" style="font-size: 0.82rem; border-radius: 8px;">
                                                <div class="pt-2 pb-1">
                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #fff8e6; color: #f0ad4e; font-size: 24px; border: 2px solid #ffeeba;">
                                                        <i class="fas fa-exclamation"></i>
                                                    </div>
                                                </div>
                                                <div class="modal-body px-2 py-2">
                                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Apakah Anda yakin?</h5>
                                                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Data alat beserta riwayat kalibrasinya akan dihapus permanen!</p>
                                                </div>
                                                <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                                                    <form action="{{ route('alat.destroy', $item->alat_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm py-1.5 px-3.5 fw-semibold rounded-2" style="font-size: 0.78rem;">Ya, Hapus!</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary btn-sm py-1.5 px-3.5 fw-semibold rounded-2" data-bs-dismiss="modal" style="font-size: 0.78rem;">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="22" class="text-center text-muted py-3">Tidak ada data alat yang ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 340px;">
        <div class="modal-content text-center">
            <div class="modal-header text-white py-2 px-3" style="background-color: #1b3152;">
                <h5 class="modal-title" id="qrModalLabel" style="font-size: 0.88rem;">QR Code Alat</h5>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3 px-2">
                <div id="qrCardContainer" class="p-2 bg-white d-inline-block rounded shadow-sm">
                    <h6 id="modalNamaAlat" class="fw-bold text-primary mb-1" style="font-size: 0.9rem;"></h6>
                    <p id="modalKodeAlat" class="text-muted small mb-2" style="font-size: 0.78rem;"></p>

                    <div id="modalQrContainer" class="p-2 bg-light d-inline-block shadow-sm rounded"></div>
                </div>   
            </div>
            <div class="modal-footer justify-content-center py-2 px-2">
                <button type="button" class="btn btn-secondary btn-sm py-1.5 px-3.5" data-bs-dismiss="modal" style="font-size: 0.78rem;">Tutup</button>
                <button type="button" id="btnDownloadQr" class="btn btn-success btn-sm py-1.5 px-3.5" style="font-size: 0.78rem;"><i class="fas fa-download me-1"></i> Unduh</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const filterKondisi = document.getElementById('filterKondisi');
    const filterForm = document.getElementById('filterForm');

    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            filterForm.submit();
        }, 500);
    });

    filterStatus.addEventListener('change', function() {
        filterForm.submit();
    });

    filterKondisi.addEventListener('change', function() {
        filterForm.submit();
    });
});

const qrModal = document.getElementById('qrModal');
qrModal.addEventListener('show.bs.modal', function (event) {
    const trigger = event.relatedTarget;
    const qrSvg = trigger.getAttribute('data-qrsvg');
    const namaAlat = trigger.getAttribute('data-alatanama');
    const kodeAlat = trigger.getAttribute('data-alatkode');

    document.getElementById('modalNamaAlat').textContent = namaAlat;
    document.getElementById('modalKodeAlat').textContent = 'Kode: ' + kodeAlat;

    const container = document.getElementById('modalQrContainer');
    container.innerHTML = qrSvg;

    const btnDownload = document.getElementById('btnDownloadQr');
    btnDownload.onclick = function() {
        const cardElement = document.getElementById('qrCardContainer');
        const kodeAlatVal = kodeAlat.replace(/[^a-zA-Z0-9]/g, '_');

        html2canvas(cardElement, {
            scale: 3, 
            backgroundColor: '#ffffff'
        }).then(canvas => {
            const pngUrl = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = 'QRCode-' + kodeAlatVal + '.png';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }).catch(error => {
            console.error('Gagal mendownload QR Code:', error);
        });
    };
});
</script>
@endsection