@extends('layouts.app')

@section('content')

<style>
    :root {
        --brand-navy: #1b3a5c;
        --brand-navy-light: #2c5282;
    }

    .card-header-custom {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.5rem 0.9rem;
    }

    .table-header-custom,
    .table-header-custom tr {
        background-color: var(--brand-navy) !important;
        color: #ffffff !important;
        border-color: var(--brand-navy-light) !important;
    }
    .table-header-custom th {
        background-color: var(--brand-navy) !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
    }

    .modal-header-brand {
        background-color: var(--brand-navy) !important;
        color: #ffffff !important;
    }
    .modal-header-brand .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .table-responsive-custom {
        font-size: 0.68rem;
    }
    .table-responsive-custom th,
    .table-responsive-custom td {
        padding: 0.25rem 0.35rem;
        vertical-align: middle;
    }

    .badge-custom-size {
        font-size: 0.55rem;
        padding: 0.2em 0.4em;
    }

    .btn-brand-standard {
        background-color: var(--brand-navy);
        border-color: var(--brand-navy);
        color: #ffffff;
        font-size: 0.8rem;
        padding: 0.35rem 1rem;
        border-radius: 0.375rem; 
        transition: transform 0.15s, background-color 0.15s;
    }
    .btn-brand-standard:hover {
        background-color: var(--brand-navy-light);
        border-color: var(--brand-navy-light);
        color: #ffffff;
        transform: translateY(-2px);
    }
    .btn-success-standard {
        background-color: #198754;
        border-color: #198754;
        color: #ffffff;
        font-size: 0.8rem;
        padding: 0.35rem 1rem;
        border-radius: 0.375rem;
        transition: transform 0.15s, background-color 0.15s;
    }
    .btn-success-standard:hover {
        background-color: #157347;
        border-color: #146c43;
        color: #ffffff;
        transform: translateY(-2px);
    }
    .btn-sm {
        font-size: 0.75rem;
    }
    .form-control-sm,
    .form-select-sm {
        font-size: 0.78rem;
    }

    .alert-dismissible {
        position: relative;
        padding-right: 3rem;
    }
    .alert-dismissible .btn-close {
        position: absolute !important;
        top: 50% !important;
        right: 1rem !important;
        transform: translateY(-50%) !important;
        margin: 0 !important;
        padding: 0.5rem;
    }
    .modal {
        overflow: visible !important;
    }
    .modal-body {
        overflow: visible !important;
    }

    .swal2-popup {
        font-size: 0.85rem !important;
        padding: 1.5rem 1.25rem 1.75rem !important;
        border-radius: 1rem !important;
        width: 30em !important;
    }
    .swal2-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        padding: 0 !important;
        margin-bottom: 0.4rem !important;
        color: #212529 !important;
    }
    .swal2-html-container {
        font-size: 0.85rem !important;
        color: #6c757d !important;
        margin: 0.4rem 0.25rem 0 !important;
    }
    .swal2-icon {
        width: 3.6em !important;
        height: 3.6em !important;
        margin: 0.3em auto 0.7em !important;
    }
    .swal2-icon .swal2-icon-content {
        font-size: 2em !important;
    }
    .swal2-actions {
        margin-top: 1.35rem !important;
        gap: 0.75rem !important;
    }
    .swal2-styled {
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        padding: 0.55rem 1.3rem !important;
        border-radius: 0.4rem !important;
        white-space: nowrap !important;
        line-height: 1.3 !important;
    }
    .custom-dropdown-item:hover, 
    .custom-dropdown-item:focus {
        background-color: rgba(27, 58, 92, 0.15) !important;
        color: #1b3a5c !important;
    }
</style>

<div class="container-fluid px-4">

    @php
        $barangHabisCount = 0;
        $barangMenipisCount = 0;
        foreach($barang as $item) {
            $saldoAwal = $item->saldo_awal ?? 0;
            $penerimaan = $item->penerimaan ?? 0;
            $pengeluaran = $item->pengeluaran ?? 0;
            $saldoAkhir = ($saldoAwal + $penerimaan) - $pengeluaran;
            if ($saldoAkhir <= 0) {
                $barangHabisCount++;
            } elseif ($saldoAkhir <= $item->minimal_stok) {
                $barangMenipisCount++;
            }
        }
    @endphp

    @if($barangHabisCount > 0 || $barangMenipisCount > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm py-2 d-flex align-items-center" role="alert" style="font-size: 0.85rem;">
            <div class="flex-grow-1">
                <i class="fas fa-exclamation-triangle me-2"></i> <strong>Perhatian!</strong>
                @if($barangHabisCount > 0)
                    Terdapat <strong>{{ $barangHabisCount }} barang</strong> yang stoknya sudah <strong>Habis</strong>.
                @endif
                @if($barangMenipisCount > 0)
                    Terdapat <strong>{{ $barangMenipisCount }} barang</strong> yang <strong>Stok Menipis</strong>.
                @endif
                Mohon segera lakukan pengecekan
            </div>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="fw-bold mb-0">Data Inventory Barang/Bahan</h5>
        <div>
                @if(Auth::user()->hasModulAccess('pengadaan'))
                <a href="{{ route('pengadaan.index') }}" class="btn btn-brand-standard shadow-sm">
                    <i class="fas fa-truck-loading me-1"></i> Cek Pengadaan
                </a>
                @endif
                <button type="button" class="btn btn-success-standard shadow-sm" data-bs-toggle="modal" data-bs-target="#cetakPeriodeModal">
                    <i class="fas fa-print me-1"></i> Cetak Laporan Periode
                </button>
            @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
            <a href="{{ route('barang.create') }}" class="btn btn-brand-standard shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Barang/Bahan
            </a>
            @endif
        </div>
    </div>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <form action="{{ route('barang.index') }}" method="GET" id="filterForm" class="row g-2 mb-3 align-items-center live-search-form" data-target="#table-container">
                <div class="col-md-7">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari Nama Barang, Kode, atau Satuan..." value="{{ $search ?? '' }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="filter_kondisi" id="filterKondisi" class="form-select form-select-sm">
                        <option value="">-- Filter Kondisi Barang --</option>
                        <option value="baik" {{ (isset($filterKondisi) && $filterKondisi == 'baik') ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ (isset($filterKondisi) && $filterKondisi == 'rusak') ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Reset"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>

            <div class="table-responsive" id="table-container">
                <table class="table table-bordered table-striped align-middle text-center table-responsive-custom">
                    <thead class="table-header-custom text-center">
                        <tr>
                            <th rowspan="2" class="text-center align-middle" style="width: 35px;">No.</th>
                            <th rowspan="2" class="text-center align-middle">Nama Barang/Bahan</th>
                            <th rowspan="2" class="text-center align-middle">Satuan</th>
                            <th rowspan="2" class="text-center align-middle">Kode Barang/Bahan</th>
                            <th rowspan="2" class="text-center align-middle">Minimal Stock</th>
                            <th rowspan="2" class="text-center align-middle">Saldo Awal</th>
                            <th colspan="2" class="text-center align-middle">Jumlah</th>
                            <th rowspan="2" class="text-center align-middle">Saldo Akhir<br><small>(Sisa Stock)</small></th>
                            <th rowspan="2" class="text-center align-middle">Harga Rata-rata Tertimbang</th>
                            <th rowspan="2" class="text-center align-middle">Nilai</th>
                            <th rowspan="2" class="text-center align-middle">Kondisi</th>
                            <th rowspan="2" class="text-center align-middle">Tanggal Expired Date</th>
                            <th rowspan="2" class="text-center align-middle" style="width: 80px;">Aksi</th>
                        </tr>
                        <tr>
                            <th>Penerimaan</th>
                            <th>Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang as $index => $item)
                        @php
                            $saldoAwal = $item->saldo_awal ?? 0;
                            $penerimaan = $item->penerimaan ?? 0;
                            $pengeluaran = $item->pengeluaran ?? 0;
                            $saldoAkhir = ($saldoAwal + $penerimaan) - $pengeluaran;

                            $hargaRata = $item->harga_rata ?? 0;
                            $nilaiTotal = $saldoAkhir * $hargaRata;

                            $isHabis = $saldoAkhir <= 0;
                            $isMenipis = !$isHabis && ($saldoAkhir <= $item->minimal_stok);
                        @endphp
                        <tr class="{{ $isHabis ? 'table-danger' : ($isMenipis ? 'table-warning' : '') }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold text-start">{{ $item->nama_barang }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td><code class="fw-bold">{{ $item->kode_barang }}</code></td>
                            <td>{{ number_format($item->minimal_stok, 0, ',', '.') }}</td>
                            <td>{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                            <td>{{ number_format($penerimaan, 0, ',', '.') }}</td>
                            <td>{{ number_format($pengeluaran, 0, ',', '.') }}</td>
                            <td class="fw-bold {{ $isHabis ? 'text-danger' : '' }}">
                                {{ number_format($saldoAkhir, 0, ',', '.') }}
                                @if($isHabis)
                                    <a href="{{ route('pengadaan.index', $item->pengadaan_id ?? 1) }}" class="badge bg-danger mt-1 d-block text-decoration-none text-white shadow-sm badge-custom-size" title="Klik untuk atur stok barang">
                                        <i class="fas fa-times-circle"></i> Habis
                                    </a>
                                @elseif($isMenipis)
                                    <a href="{{ route('pengadaan.index', $item->pengadaan_id ?? 1) }}" class="badge bg-warning text-dark mt-1 d-block text-decoration-none shadow-sm badge-custom-size" title="Klik untuk atur stok barang">
                                        <i class="fas fa-exclamation-triangle"></i> Stok Menipis
                                    </a>
                                @endif
                            </td>
                            <td>Rp {{ number_format($item->harga_rata, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($nilaiTotal, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-custom-size bg-{{ $item->kondisi == 'baik' ? 'success' : 'danger' }}">
                                    {{ ucfirst($item->kondisi) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $nearestBatch = $item->transaksiBarang()
                                        ->select('tgl_exp', \Illuminate\Support\Facades\DB::raw('SUM(jumlah_penerimaan) - SUM(jumlah_pengeluaran) as sisa_stok'))
                                        ->whereNotNull('tgl_exp')
                                        ->groupBy('tgl_exp')
                                        ->having('sisa_stok', '>', 0)
                                        ->orderBy('tgl_exp', 'asc')
                                        ->first();
                                @endphp

                                @if($nearestBatch)
                                    {{ \Carbon\Carbon::parse($nearestBatch->tgl_exp)->format('d M Y') }}
                                    <br>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none mt-1" data-bs-toggle="modal" data-bs-target="#modalBatch{{ $item->barang_id }}" style="font-size: 0.68rem;">
                                        <i class="fas fa-history"></i> Detail Stok
                                    </button>
                                @else
                                    {{ $item->tgl_exp ? \Carbon\Carbon::parse($item->tgl_exp)->format('d M Y') : '-' }}
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                                    <a href="{{ route('barang.edit', $item->barang_id) }}" class="btn btn-warning btn-sm py-0 px-1" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('barang.destroy', $item->barang_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm py-0 px-1" title="Hapus" onclick="confirmDelete(this, {{ $saldoAkhir }}, '{{ addslashes($item->nama_barang) }}')">
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
                            <td colspan="14" class="text-center text-muted py-3">Belum ada data barang persediaan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($barang as $item)
<div class="modal fade" id="modalBatch{{ $item->barang_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-brand">
                <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-boxes me-2"></i>Rincian Batch & Expired: {{ $item->nama_barang }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-header-custom text-center" style="font-size: 0.78rem;">
                            <tr>
                                <th>Tanggal Masuk</th>
                                <th>Sisa Stok Batch</th>
                                <th>Tanggal Expired</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.78rem;">
                            @php
                                $itemBatches = $item->transaksiBarang()
                                                ->select('tgl_exp', 'created_at', \Illuminate\Support\Facades\DB::raw('SUM(jumlah_penerimaan) - SUM(jumlah_pengeluaran) as sisa_stok'))
                                                ->where('jumlah_penerimaan', '>', 0)
                                                ->groupBy('tgl_exp', 'created_at')
                                                ->having('sisa_stok', '>', 0)
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                            @endphp
                            @forelse($itemBatches as $batch)
                                <tr>
                                    <td class="text-center">
                                        {{ $batch->created_at ? \Carbon\Carbon::parse($batch->created_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="text-center fw-bold" style="color: var(--brand-navy);">{{ (float)$batch->sisa_stok }} {{ $item->satuan }}</td>
                                    <td class="text-center">
                                        {{ $batch->tgl_exp ? \Carbon\Carbon::parse($batch->tgl_exp)->format('d M Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Semua stok batch sudah habis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info py-2 mb-0" style="font-size: 0.75rem;">
                    <i class="fas fa-info-circle me-1"></i> Utamakan menggunakan stok dari tanggal expired yang paling awal (Sistem FEFO).
                </div>
            </div>
            <div class="modal-footer bg-light py-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="cetakPeriodeModal" tabindex="-1" aria-labelledby="cetakPeriodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <form action="{{ route('barang.cetak-periode') }}" method="GET" target="_blank">
                <div class="modal-header modal-header-brand" style="background-color: var(--brand-navy); color: #ffffff;">
                    <h5 class="modal-title fs-6 fw-bold" id="cetakPeriodeModalLabel"><i class="fas fa-print me-2"></i>Cetak Laporan Periode</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3" style="overflow: visible;">
                    
                    <div class="mb-3 position-relative">
                        <label class="form-label small fw-bold">Bulan</label>
                        @php
                            $bulans = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            $currentMonth = date('n');
                        @endphp
                        
                        <input type="hidden" name="bulan" id="selected_bulan" value="{{ $currentMonth }}">
                        
                        <div class="dropdown">
                            <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center border form-control form-control-sm py-2" type="button" id="dropdownBulanBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="bulan_label" class="fs-6">{{ $bulans[$currentMonth] }}</span>
                                <i class="fas fa-chevron-down text-muted small"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownBulanBtn" style="max-height: 160px; overflow-y: auto;">
                                @foreach($bulans as $key => $namaBulan)
                                    <li><a class="dropdown-item py-2 px-3 bulan-option custom-dropdown-item" href="#" data-value="{{ $key }}" data-text="{{ $namaBulan }}">{{ $namaBulan }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-label small fw-bold">Tahun</label>
                        @php 
                            $tahunSekarang = date('Y'); 
                        @endphp
                        
                        <input type="hidden" name="tahun" id="selected_tahun" value="{{ $tahunSekarang }}">
                        
                        <div class="dropdown">
                            <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center border form-control form-control-sm py-2" type="button" id="dropdownTahunBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="tahun_label" class="fs-6">{{ $tahunSekarang }}</span>
                                <i class="fas fa-chevron-down text-muted small"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownTahunBtn" style="max-height: 160px; overflow-y: auto;">
                                @for($i = $tahunSekarang; $i >= 2020; $i--)
                                    <li><a class="dropdown-item py-2 px-3 tahun-option custom-dropdown-item" href="#" data-value="{{ $i }}" data-text="{{ $i }}">{{ $i }}</a></li>
                                @endfor
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
                    <!-- Menggunakan background birdong dan ikon cetak (fas fa-print) -->
                    <button type="submit" class="btn btn-sm px-3 text-white" style="background-color: var(--brand-navy); border-color: var(--brand-navy);">
                        <i class="fas fa-print me-1"></i> Cetak PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.bulan-option').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                let val = this.getAttribute('data-value');
                let txt = this.getAttribute('data-text');
                document.getElementById('selected_bulan').value = val;
                document.getElementById('bulan_label').innerText = txt;
            });
        });

        document.querySelectorAll('.tahun-option').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                let val = this.getAttribute('data-value');
                let txt = this.getAttribute('data-text');
                document.getElementById('selected_tahun').value = val;
                document.getElementById('tahun_label').innerText = txt;
            });
        });
    });
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(button, saldoAkhir, namaBarang) {
    if (saldoAkhir > 0) {
        Swal.fire({
            title: 'Tidak Dapat Dihapus!',
            text: `Stok barang "${namaBarang}" masih tersisa ${saldoAkhir}. Barang hanya bisa dihapus jika stok sudah habis (0)`,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Mengerti'
        });
    } else {
        // Tampilan SweetAlert disamakan persis dengan gaya desain modal alat
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data barang beserta riwayat transaksi/stoknya akan dihapus permanen!',
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
}

document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchInput');
    const filterKondisi = document.getElementById('filterKondisi');
    const filterForm = document.getElementById('filterForm');

    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            filterForm.submit();
        }, 500);
    });

    filterKondisi.addEventListener('change', function() {
        filterForm.submit();
    });
});
</script>
@endpush
@endsection