@extends('layouts.app')

@push('styles')
<style>
    .card-header-custom {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .table-header-custom {
        background-color: #212529;
        color: white;
    }
    .table-responsive-custom {
        font-size: 0.75rem;
    }
    .badge-custom-size {
        font-size: 0.65rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Inventori Barang Persediaan (Stock)</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Inventori Barang</li>
    </ol>

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
                <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Barang</a>
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
                    <thead class="table-header-custom align-middle">
                        <tr>
                            <th rowspan="2" style="width: 35px;">No.</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">Satuan</th>
                            <th rowspan="2">Kode Barang</th>
                            <th rowspan="2">Minimal Stock</th>
                            <th rowspan="2">Saldo Awal</th>
                            <th colspan="2">Jumlah</th>
                            <th rowspan="2">Saldo Akhir<br><small>(Sisa Stock)</small></th>
                            <th rowspan="2">Harga Rata-rata Tertimbang</th>
                            <th rowspan="2">Nilai</th>
                            <th rowspan="2">Kondisi</th>
                            <th rowspan="2">Tanggal Expired Date</th>
                            <th rowspan="2" style="width: 80px;">Aksi</th>
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
                            <td>{{ $item->tgl_exp ? \Carbon\Carbon::parse($item->tgl_exp)->format('d M Y') : '-' }}</td>
                            <td class="text-nowrap">
                                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                                    <a href="{{ route('barang.edit', $item->barang_id) }}" class="btn btn-warning btn-sm py-0 px-1" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('barang.destroy', $item->barang_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm py-0 px-1" title="Hapus" onclick="confirmDelete(this)"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="text-center text-muted py-3">Belum ada data barang persediaan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
function confirmDelete(button) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data barang akan dihapus permanen!",
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