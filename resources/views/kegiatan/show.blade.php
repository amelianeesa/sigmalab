@extends('layouts.app')

@section('content')
<div class="container-fluid">
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
                            <i class="fas fa-lock me-2"></i> Kegiatan ini sudah <strong>{{ ucfirst($kegiatan->status_kegiatan) }}</strong>. Anda tidak dapat menambahkan hasil uji baru.
                        </div>
                    @else
                        @can('create', App\Models\HasilUji::class)
                        <form action="{{ route('hasil-uji.store') }}" method="POST" class="row g-3 align-items-end">
                            @csrf
                            <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->kegiatan_id }}">
                            
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Pilih Parameter Uji <span class="text-danger">*</span></label>
                                <select name="parameter_uji_id" class="form-select" required>
                                    <option value="">-- Pilih Parameter --</option>
                                    @foreach($parameterList as $param)
                                        <option value="{{ $param->parameter_uji_id }}" data-rumus="{{ $param->rumus_kalkulasi ?? '' }}">
                                            {{ $param->nama_parameter }} (Batas: {{ $param->batas_bawah }} - {{ $param->batas_atas }} {{ $param->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7" id="dynamicInputContainer">
                                <label class="form-label fw-bold">Nilai Hasil <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.0001" name="nilai_hasil" class="form-control" id="nilai_hasil_input" required placeholder="Contoh: 15.5">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-1"></i> Simpan Hasil
                                </button>
                            </div>
                        </form>
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
                                    @foreach($kegiatan->hasilUji as $hasil)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="fw-bold">{{ $hasil->parameterUji ? $hasil->parameterUji->nama_parameter : '-' }}</span><br>
                                                <small class="text-muted">{{ $hasil->parameterUji ? $hasil->parameterUji->satuan : '' }}</small>
                                            </td>
                                            <td class="text-center fs-5 fw-bold">{{ $hasil->nilai_hasil }}</td>
                                            <td class="text-center">
                                                {{ $hasil->parameterUji ? $hasil->parameterUji->batas_bawah . ' - ' . $hasil->parameterUji->batas_atas : '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if($hasil->status_berketerimaan == 'inlier')
                                                    <span class="badge bg-success p-2"><i class="fas fa-check-circle me-1"></i> Inlier</span>
                                                @elseif($hasil->status_berketerimaan == 'outlier')
                                                    <span class="badge bg-danger p-2"><i class="fas fa-times-circle me-1"></i> Outlier</span>
                                                @else
                                                    {{ $hasil->status_berketerimaan ?? '-' }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @can('view', $hasil)
                                                    <a href="{{ route('hasil-uji.show', $hasil->hasil_uji_id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail"><i class="fas fa-eye"></i> Detail</a>
                                                @endcan
                                            </td>
                                        </tr>
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
