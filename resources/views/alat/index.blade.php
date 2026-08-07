@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Alat & Kalibrasi</h1>
    <!-- <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-3 fw-semibold rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a> -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Alat & Kalibrasi</li>
    </ol>

    @php
        $alatWarningCount = 0;
        foreach($alat as $item) {
            $kalibrasi = $item->riwayatKalibrasi()->latest('tgl_kalibrasi')->first();
            if ($kalibrasi && $kalibrasi->tgl_akhir) {
                $tglAkhir = \Carbon\Carbon::parse($kalibrasi->tgl_akhir);
                $sisaHari = \Carbon\Carbon::now()->startOfDay()->diffInDays($tglAkhir, false);
                if ($sisaHari <= 30) {
                    $alatWarningCount++;
                }
            }
        }
    @endphp

    @if($alatWarningCount > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Perhatian!</strong> Terdapat <strong>{{ $alatWarningCount }} alat</strong> yang masa kalibrasinya sudah kedaluarsa atau akan segera berakhir (dalam 30 hari ke depan). Mohon segera jadwalkan kalibrasi ulang.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div><i class="fas fa-tools me-1"></i> Data Master Alat & Informasi Kalibrasi</div>
            @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
            <a href="{{ route('alat.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Alat</a>
            @endif
        </div>
        <div class="card-body">
            
            <form action="{{ route('alat.index') }}" method="GET" id="filterForm" class="row g-2 mb-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari Nama Alat, Kode, atau Merk..." value="{{ $search ?? '' }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="filter_status" id="filterStatus" class="form-select form-select-sm">
                        <option value="">-- Filter Status Kalibrasi --</option>
                        <option value="aktif" {{ (isset($filterStatus) && $filterStatus == 'aktif') ? 'selected' : '' }}>Aktif (> 30 Hari)</option>
                        <option value="segera" {{ (isset($filterStatus) && $filterStatus == 'segera') ? 'selected' : '' }}>Segera Berakhir (&le; 30 Hari)</option>
                        <option value="kedaluarsa" {{ (isset($filterStatus) && $filterStatus == 'kedaluarsa') ? 'selected' : '' }}>Kedaluarsa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="filter_kondisi" id="filterKondisi" class="form-select form-select-sm">
                        <option value="">-- Filter Kondisi Barang --</option>
                        <option value="baik" {{ (isset($filterKondisi) && $filterKondisi == 'baik') ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ (isset($filterKondisi) && $filterKondisi == 'rusak') ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Reset"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center" style="font-size: 0.73rem;">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th rowspan="2" style="width: 40px;">No.</th>
                            <th rowspan="2" style="width: 80px;">QR Code</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">CODE</th>
                            <th colspan="5">Spesifikasi</th>
                            <th rowspan="2">Kondisi Barang</th>
                            <th rowspan="2">Status Barang</th>
                            <th rowspan="2">No. Sertifikat/<br>Perijinan</th>
                            <th rowspan="2">Interval Kalibrasi</th>
                            <th colspan="2">Periode Kalibrasi/<br>Perijinan</th>
                            <th rowspan="2">Unit Kerja Pemilik</th>
                            <th rowspan="2">Lembaga Kalibrasi</th>
                            <th colspan="4">Kalibrasi</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Merk / Type</th>
                            <th>Serial Number</th>
                            <th>Warna</th>
                            <th>Ukuran</th>
                            <th>Unit Pemilik</th>
                            <th>Tgl Kalibrasi</th>
                            <th>Masa Berakhir<br>Kalibrasi</th>
                            <th>Jenis Kalibrasi</th>
                            <th>Range / Kapasitas</th>
                            <th>Faktor Koreksi</th>
                            <th>Signifikan (Ya/Tidak)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alat as $index => $item)
                        @php
                            $kalibrasiTerakhir = $item->riwayatKalibrasi()->latest('tgl_kalibrasi')->first();
                            $jenisKalibrasi = optional($kalibrasiTerakhir)->jenis_kalibrasi;
                            $signifikan = optional($kalibrasiTerakhir)->signifikan;

                            $statusKalibrasiBadge = '';
                            if ($kalibrasiTerakhir && $kalibrasiTerakhir->tgl_akhir) {
                                $tglAkhir = \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_akhir);
                                $sekarang = \Carbon\Carbon::now()->startOfDay();
                                $sisaHari = $sekarang->diffInDays($tglAkhir, false);

                                if ($sisaHari < 0) {
                                    $statusKalibrasiBadge = '<span class="badge bg-danger mt-1 d-block" style="font-size: 0.65rem;"><i class="fas fa-times-circle"></i> Kedaluarsa</span>';
                                } elseif ($sisaHari <= 30) {
                                    $statusKalibrasiBadge = '<span class="badge bg-warning text-dark mt-1 d-block" style="font-size: 0.65rem;" title="Sisa ' . $sisaHari . ' hari lagi"><i class="fas fa-clock"></i> Segera Berakhir (' . $sisaHari . 'h)</span>';
                                }
                            }

                            $qrData = "Kode Alat: {$item->kode_alat}\n" .
                                      "Nama Alat: {$item->nama_alat}\n";

                            $qrSvgCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(40)->generate($qrData);
                            $qrSvgLarge = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($qrData);
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
                            <td class="fw-bold text-start">{{ $item->nama_alat }}</td>
                            <td><code class="fw-bold">{{ $item->kode_alat }}</code></td>
                            <td class="text-start">{{ $item->merk_tipe ?? '-' }}</td>
                            <td>{{ $item->no_seri ?? '-' }}</td>
                            <td>{{ $item->warna ?? '-' }}</td>
                            <td>{{ $item->ukuran ?? '-' }}</td>
                            <td>{{ $item->unit_kerja_pemilik ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->kondisi_barang == 'baik' ? 'success' : 'danger' }}">
                                    {{ ucfirst($item->kondisi_barang) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->status_barang == 'terpakai' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($item->status_barang) }}
                                </span>
                            </td>
                            <td>{{ $kalibrasiTerakhir->no_sertifikat ?? '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->interval_kalibrasi ?? '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->tgl_kalibrasi ?? '-' }}</td>
                            <td>
                                {{ $kalibrasiTerakhir->tgl_akhir ?? '-' }}
                                {!! $statusKalibrasiBadge !!}
                            </td>
                            <td>{{ $item->unit_kerja_pemilik ?? '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->lembaga_kalibrasi ?? '-' }}</td>
                            <td>{{ $jenisKalibrasi ? ucfirst($jenisKalibrasi) : '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->range_kapasitas ?? '-' }}</td>
                            <td>{{ $kalibrasiTerakhir->faktor_koreksi ?? '-' }}</td>
                            <td>{{ ucfirst($signifikan ?? '-') }}</td>

                            <td class="text-nowrap">
                                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                                    <a href="{{ route('alat.edit', $item->alat_id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('alat.destroy', $item->alat_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" onclick="confirmDelete(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="22" class="text-center text-muted">Tidak ada data alat yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fs-6" id="qrModalLabel">QR Code Alat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <h6 id="modalNamaAlat" class="fw-bold text-primary mb-1"></h6>
                <p id="modalKodeAlat" class="text-muted small mb-3"></p>
                
                <div id="modalQrContainer" class="p-3 bg-light d-inline-block shadow-sm rounded mb-3"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="btnDownloadQr" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Unduh QR Code</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(button) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data alat beserta riwayat kalibrasinya akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

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
        const svgElement = container.querySelector('svg');
        const svgString = new XMLSerializer().serializeToString(svgElement);
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.fillStyle = "#FFFFFF";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);

            const pngUrl = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = 'QRCode-' + kodeAlat.replace(/[^a-zA-Z0-9]/g, '_') + '.png';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        };

        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgString)));
    };
});
</script>
@endsection