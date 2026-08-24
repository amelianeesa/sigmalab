@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $kegiatan->nama_kegiatan }}</li>
        </ol>
    </nav>

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">{{ $kegiatan->nama_kegiatan }}</h1>
            <div>
                @can('update', $kegiatan)
                    <a href="{{ route('kegiatan.edit', $kegiatan->kegiatan_id) }}" class="btn btn-warning text-white">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endcan
                <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kegiatan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="20%">Nama Kegiatan</th>
                            <td width="2%">:</td>
                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kegiatan</th>
                            <td width="2%">:</td>
                            <td>{{ ucfirst($kegiatan->jenis_kegiatan) }}</td>
                        </tr>
                        <tr>
                            <th>Kode Sampel</th>
                            <td>:</td>
                            <td>{{ $kegiatan->kode_sampel ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kegiatan</th>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:</td>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>:</td>
                            <td>{{ $kegiatan->pembuatKegiatan ? $kegiatan->pembuatKegiatan->username : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Alat Digunakan</h6>
                </div>
                <div class="card-body">
                    @if($kegiatan->alatDigunakan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
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
                        <div class="alert alert-info">Belum ada alat yang digunakan dalam kegiatan ini.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personil Terlibat</h6>
                </div>
                <div class="card-body">
                    @if($kegiatan->personilTerlibat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
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
                        <div class="alert alert-info">Belum ada personil yang terlibat dalam kegiatan ini.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bahan Digunakan</h6>
                </div>
                <div class="card-body">
                    @if($kegiatan->transaksiBarang->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
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
                        <div class="alert alert-info">Belum ada bahan yang dicatat penggunaannya.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-flask me-2"></i> Workspace Hasil Pengujian</h6>
                </div>
                
                <div class="card-body bg-light border-bottom">
                    @if(in_array($kegiatan->status_kegiatan, ['selesai', 'dibatalkan']))
                        <div class="alert alert-warning m-0">
                            <i class="fas fa-lock me-2"></i> Kegiatan ini sudah <strong>{{ ucfirst($kegiatan->status_kegiatan) }}</strong>. Anda tidak dapat mengisi hasil uji.
                        </div>
                    @else
                        @can('create', App\Models\HasilUji::class)
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0">Daftar parameter di bawah ini adalah pesanan dari klien. Silakan klik tombol <strong>Input Data</strong> untuk memasukkan hasil pengujian dari instrumen Anda.</p>
                        </div>
                        @else
                        <div class="alert alert-info m-0">
                            Anda tidak memiliki hak akses untuk menginput Hasil Uji.
                        </div>
                        @endcan
                    @endif
                </div>

                <div class="card-body">
                    @if(isset($kegiatan->hasilUji) && $kegiatan->hasilUji->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Parameter Uji</th>
                                        <th class="text-center">Hasil</th>
                                        <th class="text-center">Standar (Min - Max)</th>
                                        <th class="text-center">Status</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Group by parameter ID to support rowspan for re-tests (gagal duplo)
                                        $groupedHasil = $kegiatan->hasilUji->groupBy('parameter_uji_id');
                                        $nomor = 1;
                                    @endphp
                                    @foreach($groupedHasil as $paramId => $group)
                                        @php
                                            $hasPassed = $group->whereIn('status_berketerimaan', ['inlier', 'outlier'])->count() > 0;
                                            if ($hasPassed) {
                                                $displayGroup = $group->reject(function($h) {
                                                    return in_array(strtolower($h->status_berketerimaan), ['belum_diuji', 'pending']);
                                                })->values();
                                            } else {
                                                $displayGroup = $group->values();
                                            }
                                        @endphp
                                        @foreach($displayGroup as $index => $hasil)
                                            <tr>
                                                @if($index === 0)
                                                    <td class="text-center align-middle" rowspan="{{ count($displayGroup) }}">{{ $nomor++ }}</td>
                                                    <td class="align-middle" rowspan="{{ count($displayGroup) }}">
                                                        <span class="fw-bold">{{ $hasil->parameterUji ? $hasil->parameterUji->nama_parameter : '-' }}</span><br>
                                                        <small class="text-muted">{{ $hasil->parameterUji ? $hasil->parameterUji->satuan : '' }}</small>
                                                    </td>
                                                @endif
                                                <td class="text-center align-middle">
                                                    @if(is_null($hasil->nilai_hasil))
                                                        <span class="text-muted fst-italic">Belum diinput</span>
                                                    @else
                                                        <span class="{{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'text-danger text-decoration-line-through' : 'fs-5 fw-bold' }}">
                                                            {{ number_format($hasil->nilai_hasil, 4) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $hasil->parameterUji ? $hasil->parameterUji->batas_bawah . ' - ' . $hasil->parameterUji->batas_atas : '-' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if(strtolower($hasil->status_berketerimaan) == 'pending')
                                                        <span class="badge bg-warning text-dark fs-6"><i class="fas fa-clock"></i> Menunggu Dependensi</span>
                                                    @elseif(strtolower($hasil->status_berketerimaan) == 'belum_diuji')
                                                        <span class="badge bg-light text-secondary border fs-6">Belum Diuji</span>
                                                    @elseif(strtolower($hasil->status_berketerimaan) == 'gagal_duplo')
                                                        <span class="badge bg-danger fs-6">Gagal Duplo</span>
                                                    @elseif(strtolower($hasil->status_berketerimaan) == 'inlier')
                                                        <span class="badge bg-success fs-6">Inlier</span>
                                                    @elseif(strtolower($hasil->status_berketerimaan) == 'outlier')
                                                        <span class="badge bg-danger fs-6">Outlier</span>
                                                    @else
                                                        <span class="badge bg-secondary fs-6">{{ $hasil->status_berketerimaan }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if(strtolower($hasil->status_berketerimaan) == 'belum_diuji' || strtolower($hasil->status_berketerimaan) == 'pending')
                                                        @if($hasPassed)
                                                            <!-- This case should technically not be reached anymore due to filtering above, but kept for safety -->
                                                            <button class="btn btn-sm btn-secondary" disabled title="Pengujian sudah lolos pada baris lain">
                                                                <i class="fas fa-check-double"></i> Selesai
                                                            </button>
                                                        @else
                                                            @can('create', App\Models\HasilUji::class)
                                                            <a href="{{ route('hasil-uji.edit', $hasil->hasil_uji_id) }}" class="btn btn-sm btn-primary" title="Input Data">
                                                                <i class="fas fa-keyboard"></i> Input Data
                                                            </a>
                                                            @endcan
                                                        @endif
                                                    @else
                                                        <a href="{{ route('hasil-uji.show', $hasil->hasil_uji_id) }}" class="btn btn-sm btn-info text-white" title="Detail">
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
                            <p>Belum ada hasil uji yang diinput untuk sampel/kegiatan ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paramSelect = document.querySelector('select[name="parameter_uji_id"]');
        const container = document.getElementById('dynamicInputContainer');

        if(paramSelect) {
            paramSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const rumus = selected.getAttribute('data-rumus');

                if(rumus && rumus.trim() !== '') {
                    // Extract variables from rumus (words starting with letter)
                    const vars = [...new Set(rumus.match(/[a-zA-Z][a-zA-Z0-9_]*/g) || [])];
                    
                    if(vars.length > 0) {
                        let html = `<div class="p-3 bg-white border rounded shadow-sm">
                            <div class="mb-2"><span class="badge bg-info text-dark">Rumus: ${rumus}</span></div>
                            <div class="row g-2">`;
                        
                        vars.forEach(v => {
                            html += `
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-primary small mb-1">${v} <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" name="variabel[${v}]" class="form-control form-control-sm" required placeholder="Nilai ${v}">
                                </div>
                            `;
                        });
                        
                        html += `</div></div>`;
                        container.innerHTML = html;
                        container.classList.remove('col-md-7');
                        container.classList.add('col-md-12');
                    } else {
                        renderNormalInput();
                    }
                } else {
                    renderNormalInput();
                }
            });
        }

        function renderNormalInput() {
            container.innerHTML = `
                <label class="form-label fw-bold">Nilai Hasil <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" step="0.0001" name="nilai_hasil" class="form-control" required placeholder="Contoh: 15.5">
                </div>
            `;
            container.classList.remove('col-md-12');
            container.classList.add('col-md-7');
        }
    });
</script>
@endpush
