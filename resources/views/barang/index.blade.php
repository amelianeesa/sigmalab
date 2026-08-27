@extends('layouts.app')

@section('content')

<style>
    .card-header-custom {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    /* Memaksa background dan teks header tabel menjadi hitam */
    .table-header-custom, 
    .table-header-custom th, 
    .table-header-custom tr {
        background-color: #212529 !important;
        color: #ffffff !important;
        border-color: #454d55 !important;
    }
    .table-responsive-custom {
        font-size: 0.75rem;
    }
    .badge-custom-size {
        font-size: 0.65rem;
    }
</style>

<div class="container-fluid px-4">
    <!-- <h1 class="mt-4">Laporan Inventori Barang Persediaan (Stock)</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Inventori Barang</li>
    </ol> -->

    <div class="d-flex flex-wrap gap-3 mb-4 mt-2">
        @if(Auth::user()->hasModulAccess('pengadaan'))
        <a href="{{ route('pengadaan.index') }}" class="btn btn-info rounded-pill px-4 shadow-sm text-white fw-bold" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-truck-loading me-2"></i> Cek Pengadaan Barang/Bahan
        </a>
        @endif
    </div>

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
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Perhatian!</strong> 
            @if($barangHabisCount > 0)
                Terdapat <strong>{{ $barangHabisCount }} barang</strong> yang stoknya sudah <strong>Habis</strong>. 
            @endif
            @if($barangMenipisCount > 0)
                Terdapat <strong>{{ $barangMenipisCount }} barang</strong> yang <strong>Stok Menipis</strong>. 
            @endif
            Mohon segera lakukan pengecekan
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4 shadow-sm">
        <div class="card-header card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div><i class="fas fa-boxes me-1"></i> Data Barang Persediaan & Sisa Stock</div>
            <div>
                <button type="button" class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#cetakPeriodeModal">
                    <i class="fas fa-print"></i> Cetak Laporan Periode
                </button>
                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Barang/Bahan</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            
            <form action="{{ route('barang.index') }}" method="GET" id="filterForm" class="row g-2 mb-3 align-items-center">
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

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center table-responsive-custom">
                    <thead class="table-header-custom text-center">
                        <tr>
                            <th rowspan="2" class="text-center align-middle"style="width: 35px;">No.</th>
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
                                <span class="badge bg-{{ $item->kondisi == 'baik' ? 'success' : 'danger' }}">
                                    {{ ucfirst($item->kondisi) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    // Mencari 1 batch aktif terdekat yang sisa stoknya masih > 0
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
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none mt-1" data-bs-toggle="modal" data-bs-target="#modalBatch{{ $item->barang_id }}" style="font-size: 0.70rem;">
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
                                        <button type="button" class="btn btn-danger btn-sm py-0 px-1" title="Hapus" onclick="confirmDelete(this, {{ $saldoAkhir }}, '{{ $item->nama_barang }}')">
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
            <div class="modal-header bg-light">
                <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-boxes me-2"></i>Rincian Batch & Expired: {{ $item->nama_barang }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <<thead class="table-header-custom text-center" style="font-size: 0.85rem;">
                            <tr>
                                <th>Tanggal Masuk</th>
                                <th>Sisa Stok Batch</th>
                                <th>Tanggal Expired</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.85rem;">
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
                                    <td class="text-center fw-bold text-primary">{{ (float)$batch->sisa_stok }} {{ $item->satuan }}</td>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('barang.cetak-periode') }}" method="GET" target="_blank">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6" id="cetakPeriodeModalLabel"><i class="fas fa-print me-2"></i>Cetak Laporan Periode</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select" required>
                            @php
                                $bulans = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @foreach($bulans as $key => $namaBulan)
                                <option value="{{ $key }}" {{ date('n') == $key ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select" required>
                            @php $tahunSekarang = date('Y'); @endphp
                            @for($i = $tahunSekarang; $i >= 2020; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-pdf"></i> Cetak PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
        Swal.fire({
            title: 'Konfirmasi Hapus Barang',
            text: `Stok barang "${namaBarang}" sudah habis. Apakah Anda yakin ingin menghapus barang ini secara permanen?`,
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