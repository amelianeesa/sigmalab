@extends('layouts.app')
@section('title', 'Detail Data - Kegiatan')

@section('content')
@include('kegiatan._kegiatan-style')

<div class="container-fluid kg-page pb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('verifikasi-mutu.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $kegiatan->nama_kegiatan }}</li>
        </ol>
    </nav>

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <h5 class="page-title">{{ $kegiatan->nama_kegiatan }}</h5>
        <div class="d-flex gap-2">
            @can('update', $kegiatan)
                <a href="{{ route('kegiatan.edit', $kegiatan->kegiatan_id) }}" class="btn btn-warning btn-sm text-white shadow-sm fw-semibold py-1.5 px-3 flex-fill flex-md-grow-0">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
            @endcan
            <a href="{{ route('verifikasi-mutu.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm fw-semibold py-1.5 px-3 flex-fill flex-md-grow-0">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI STATUS --}}
    @if(in_array($kegiatan->status_kegiatan, ['selesai', 'dibatalkan']))
        <div class="alert alert-warning shadow-sm mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2" role="alert">
            <div>
                <i class="fas fa-lock me-1"></i> Kegiatan ini sudah <strong>{{ ucfirst($kegiatan->status_kegiatan) }}</strong>. Seluruh data telah terkunci.
            </div>
            @if($kegiatan->status_kegiatan === 'selesai' && auth()->user()->hasRole(['koordinator_lab', 'manajer_teknis', 'admin']))
                <form action="{{ route('kegiatan.unlock', $kegiatan->kegiatan_id) }}" method="POST" class="d-grid d-md-block">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger fw-semibold shadow-sm" onclick="return confirm('Anda yakin ingin membuka kunci kegiatan ini untuk revisi? Status akan kembali menjadi Proses.')">
                        <i class="fas fa-unlock me-1"></i> Buka Kunci (Revisi)
                    </button>
                </form>
            @endif
        </div>
    @else
        @can('create', App\Models\HasilUji::class)
            <div class="alert alert-info shadow-sm mb-3" role="alert">
                <i class="fas fa-info-circle me-1"></i> Daftar parameter di bawah adalah pesanan dari klien. Klik tombol <strong>Input Data</strong> untuk memasukkan hasil pengujian dari instrumen Anda.
            </div>
        @else
            <div class="alert alert-info shadow-sm mb-3" role="alert">
                <i class="fas fa-info-circle me-1"></i> Anda tidak memiliki hak akses untuk menginput Hasil Uji.
            </div>
        @endcan
    @endif

    {{-- INFORMASI KEGIATAN --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header"><h6 class="card-title-sm">Informasi Kegiatan</h6></div>
        <div class="card-body">
            <div class="info-grid">
                <div>
                    <span class="info-label">Nama Kegiatan</span>
                    <span class="info-value">{{ $kegiatan->nama_kegiatan }}</span>
                </div>
                <div>
                    <span class="info-label">Jenis Kegiatan</span>
                    <span class="info-value">{{ ucfirst($kegiatan->jenis_kegiatan) }}</span>
                </div>
                <div>
                    <span class="info-label">Kode Sampel</span>
                    <span class="info-value">{{ $kegiatan->kode_sampel ?? '-' }}</span>
                </div>
                <div>
                    <span class="info-label">Tanggal Kegiatan</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d F Y') }}</span>
                </div>
                <div>
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($kegiatan->status_kegiatan == 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @elseif($kegiatan->status_kegiatan == 'berjalan')
                            <span class="badge bg-primary">Berjalan</span>
                        @elseif($kegiatan->status_kegiatan == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @elseif($kegiatan->status_kegiatan == 'dibatalkan')
                            <span class="badge bg-danger">Dibatalkan</span>
                        @else
                            <span class="badge bg-secondary">{{ $kegiatan->status_kegiatan }}</span>
                        @endif
                    </span>
                </div>
                <div>
                    <span class="info-label">Dibuat Oleh</span>
                    <span class="info-value">{{ $kegiatan->pembuatKegiatan ? $kegiatan->pembuatKegiatan->username : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ALAT / PERSONIL / BAHAN --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><h6 class="card-title-sm">Alat Digunakan</h6></div>
                <div class="card-body">
                    @if($kegiatan->alatDigunakan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">No</th>
                                        <th>Nama Alat</th>
                                        <th>Kode Alat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kegiatan->alatDigunakan as $alat)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $alat->nama_alat }}</td>
                                            <td>{{ $alat->kode_alat }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">Belum ada alat yang digunakan dalam kegiatan ini.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><h6 class="card-title-sm">Personil Terlibat</h6></div>
                <div class="card-body">
                    @if($kegiatan->personilTerlibat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">No</th>
                                        <th>Nama Personil</th>
                                        <th>No Induk</th>
                                        <th>Peran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kegiatan->personilTerlibat as $personil)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $personil->nama }}</td>
                                            <td>{{ $personil->no_induk ?? '-' }}</td>
                                            <td>{{ $personil->pivot->peran ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">Belum ada personil yang terlibat dalam kegiatan ini.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><h6 class="card-title-sm">Bahan Digunakan</h6></div>
                <div class="card-body">
                    @if($kegiatan->transaksiBarang->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">No</th>
                                        <th>Nama Bahan</th>
                                        <th class="text-center">Jumlah Dipakai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kegiatan->transaksiBarang as $transaksi)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $transaksi->barang ? $transaksi->barang->nama_barang : '-' }}</td>
                                            <td class="text-center">{{ (float) $transaksi->jumlah_pengeluaran }} {{ $transaksi->barang ? $transaksi->barang->satuan : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">Belum ada bahan yang dicatat penggunaannya.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- WORKSPACE HASIL PENGUJIAN --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header card-header-corp">
            <h6 class="card-title-sm"><i class="fas fa-flask me-2"></i>Workspace Hasil Pengujian</h6>
        </div>
        <div class="card-body">
            @if(isset($kegiatan->hasilUji) && $kegiatan->hasilUji->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0 text-center table-stack">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th class="text-start">Parameter Uji</th>
                                <th>Jenis Kontrol</th>
                                <th>Hasil</th>
                                <th>Standar (Min - Max)</th>
                                <th>Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Group by parameter ID untuk mendukung rowspan (uji ulang / gagal duplo)
                                $groupedHasil = $kegiatan->hasilUji->groupBy('parameter_uji_id');
                                $nomor = 1;
                                $statusMap = [
                                    'pending'     => ['bg-warning text-dark', '<i class="fas fa-clock"></i> Menunggu Dependensi'],
                                    'belum_diuji' => ['bg-light text-secondary border', 'Belum Diuji'],
                                    'gagal_duplo' => ['bg-danger', 'Gagal Duplo'],
                                    'inlier'      => ['bg-success', 'Inlier'],
                                    'outlier'     => ['bg-danger', 'Outlier'],
                                ];
                            @endphp
                            @foreach($groupedHasil as $paramId => $group)
                                @php $displayGroup = $group->values(); @endphp
                                @foreach($displayGroup as $index => $hasil)
                                    @php
                                        $st = strtolower($hasil->status_berketerimaan);
                                        [$stClass, $stLabel] = $statusMap[$st] ?? ['bg-secondary', e($hasil->status_berketerimaan)];
                                        $namaParam = $hasil->parameterUji ? $hasil->parameterUji->nama_parameter : '-';
                                    @endphp
                                    <tr>
                                        @if($index === 0)
                                            <td data-label="No" rowspan="{{ count($displayGroup) }}">{{ $nomor++ }}</td>
                                            <td data-label="Parameter" class="text-md-start" rowspan="{{ count($displayGroup) }}">
                                                <span class="fw-bold">{{ $namaParam }}</span>
                                                <small class="text-muted d-md-block ms-1 ms-md-0">{{ $hasil->parameterUji ? $hasil->parameterUji->satuan : '' }}</small>
                                            </td>
                                        @else
                                            {{-- Hanya tampil di HP agar tiap kartu uji ulang tetap jelas --}}
                                            <td data-label="Parameter" class="d-md-none"><span class="fw-bold">{{ $namaParam }}</span></td>
                                        @endif

                                        <td data-label="Jenis Kontrol">
                                            <span>
                                                @if($hasil->jenis_kontrol === 'crm')
                                                    <span class="badge text-white" style="background-color: #6f42c1;">CRM</span>
                                                @elseif($hasil->jenis_kontrol === 'in_house')
                                                    <span class="badge bg-info text-white">In-House</span>
                                                @else
                                                    <span class="badge bg-secondary text-white">Reguler</span>
                                                @endif
                                                @if($hasil->run_ke > 1)
                                                    <span class="badge bg-secondary ms-1 ms-md-0 d-md-block mt-md-1" style="font-size: 0.65rem;">Run {{ $hasil->run_ke }}</span>
                                                @endif
                                            </span>
                                        </td>

                                        <td data-label="Hasil">
                                            @if(is_null($hasil->nilai_hasil))
                                                <span class="text-muted fst-italic">Belum diinput</span>
                                            @else
                                                <span class="{{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'text-danger text-decoration-line-through' : 'fw-bold' }}" style="font-size: 0.85rem;">
                                                    {{ number_format($hasil->nilai_hasil, 4, '.', '') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td data-label="Standar">
                                            @if($hasil->jenis_kontrol === 'crm' && $kegiatan->crm_katalog_id)
                                                @php
                                                    $sertifikat = \App\Models\CrmSertifikat::where('crm_katalog_id', $kegiatan->crm_katalog_id)
                                                        ->where('parameter_uji_id', $hasil->parameter_uji_id)
                                                        ->first();
                                                @endphp
                                                @if($sertifikat)
                                                    <span class="text-primary fw-bold text-nowrap" title="Batas Sertifikat CRM">
                                                        {{ number_format($sertifikat->cert_value - $sertifikat->cert_u, 4, '.', '') }} - {{ number_format($sertifikat->cert_value + $sertifikat->cert_u, 4, '.', '') }}
                                                    </span>
                                                @else
                                                    <span class="text-danger"><i class="fas fa-exclamation-circle" title="Sertifikat CRM tidak ditemukan untuk Parameter ini"></i> Error</span>
                                                @endif
                                            @else
                                                <span class="text-nowrap">{{ $hasil->parameterUji ? number_format($hasil->parameterUji->batas_bawah, 4, '.', '') . ' - ' . number_format($hasil->parameterUji->batas_atas, 4, '.', '') : '-' }}</span>
                                            @endif
                                        </td>

                                        <td data-label="Status">
                                            <span class="badge {{ $stClass }}">{!! $stLabel !!}</span>
                                        </td>

                                        <td class="td-aksi">
                                            @if($st == 'belum_diuji' || $st == 'pending')
                                                @can('create', App\Models\HasilUji::class)
                                                    @if($hasil->jenis_kontrol === 'in_house' && empty($hasil->parameterUji->sampel_inhouse_id))
                                                        <button type="button" class="btn btn-sm btn-warning text-dark fw-semibold btn-butuh-acuan" style="font-size: 0.72rem;"
                                                                data-param-id="{{ $hasil->parameter_uji_id }}"
                                                                data-param-nama="{{ $hasil->parameterUji->nama_parameter }}"
                                                                title="Nilai Acuan Belum Tersedia">
                                                            <i class="fas fa-exclamation-triangle"></i> Butuh Nilai Acuan
                                                        </button>
                                                    @else
                                                        <a href="{{ route('hasil-uji.edit', $hasil->hasil_uji_id) }}" class="btn btn-sm btn-corporate-blue shadow-sm" style="font-size: 0.72rem;" title="Input Data">
                                                            <i class="fas fa-keyboard"></i> Input Data
                                                        </a>
                                                    @endif
                                                @endcan
                                            @else
                                                <a href="{{ route('hasil-uji.show', $hasil->hasil_uji_id) }}" class="btn btn-sm btn-info text-white shadow-sm" style="font-size: 0.72rem;" title="Detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-flask fa-3x mb-3 text-light"></i>
                    <p class="mb-0">Belum ada hasil uji yang diinput untuk sampel/kegiatan ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Smart Intercept untuk "Butuh Nilai Acuan"
    document.querySelectorAll('.btn-butuh-acuan').forEach(btn => {
        btn.addEventListener('click', function () {
            const paramNama = this.getAttribute('data-param-nama');
            const paramId = this.getAttribute('data-param-id');

            Swal.fire({
                icon: 'warning',
                title: 'Nilai Acuan Belum Tersedia',
                text: `Pengujian harian tidak dapat dilakukan karena Parameter ${paramNama} belum memiliki Nilai Acuan In-House. Bagaimana Anda ingin menyelesaikannya?`,
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="fas fa-flask"></i> Mulai Uji Homogenitas Baru',
                denyButtonText: '<i class="fas fa-history"></i> Input Nilai Historis',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#1b3152',
                denyButtonColor: '#ffc107',
                customClass: { denyButton: 'text-dark fw-bold' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `{{ route('qc-inhouse.create') }}?parameter_uji_id=${paramId}`;
                } else if (result.isDenied) {
                    window.location.href = `{{ url('parameter-uji') }}/${paramId}/edit`;
                }
            });
        });
    });
});
</script>
@endpush