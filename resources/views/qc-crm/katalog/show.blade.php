@extends('layouts.app')
@section('title', 'Detail Data - crm-katalog')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $katalog->nomor_lot }} — Detail Botol</li>
    </x-qc-breadcrumb>

    <div class="mb-4 mt-3">
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-info-circle text-primary me-2"></i>Detail Botol CRM</h2>
        <p class="text-muted mb-0">Kelola nilai sertifikat parameter uji untuk botol ini.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Botol -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Botol</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" width="40%">Nomor Lot</td>
                            <td class="fw-bold"><span class="badge bg-primary fs-6">{{ $katalog->nomor_lot }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor Sertifikat</td>
                            <td class="fw-bold">{{ $katalog->nomor_sertifikat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Produk</td>
                            <td class="fw-bold">{{ $katalog->nama_produk }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Produsen</td>
                            <td class="fw-bold">{{ $katalog->produsen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Expired Date</td>
                            <td class="fw-bold">
                                @if($katalog->tanggal_expired)
                                    @if($katalog->tanggal_expired < now())
                                        <span class="text-danger">{{ $katalog->tanggal_expired->format('d M Y') }} (Expired)</span>
                                    @else
                                        {{ $katalog->tanggal_expired->format('d M Y') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="fw-bold">
                                @if($katalog->is_active)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times-circle"></i> Inaktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Daftar Parameter Sertifikat -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold"><i class="fas fa-list text-primary me-2"></i>Nilai Sertifikat (True Value)</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addParamModal">
                        <i class="fas fa-plus"></i> Tambah Parameter
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Parameter Uji</th>
                                    <th>True Value (Sertifikat)</th>
                                    <th>Ketidakpastian (±)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($katalog->sertifikats as $s)
                                <tr>
                                    <td class="fw-bold">{{ $s->parameterUji->nama_parameter ?? 'Unknown' }}</td>
                                    <td><span class="badge bg-success fs-6">{{ $s->cert_value }}</span></td>
                                    <td><span class="text-muted fw-bold">± {{ $s->cert_u }}</span></td>
                                    <td>
                                        <form action="{{ route('crm-katalog.destroy-sertifikat', [$katalog->id, $s->id]) }}" method="POST" onsubmit="return confirm('Hapus parameter ini dari sertifikat botol?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada parameter yang didaftarkan pada botol ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Parameter -->
<div class="modal fade" id="addParamModal" tabindex="-1" aria-labelledby="addParamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('crm-katalog.store-sertifikat', $katalog->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Parameter ke Botol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Parameter Uji <span class="text-danger">*</span></label>
                        <select name="parameter_uji_id" class="form-select" required>
                            <option value="">-- Pilih Parameter --</option>
                            @foreach($parameterList as $p)
                                <option value="{{ $p->parameter_uji_id }}">{{ $p->nama_parameter }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">True Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.0001" class="form-control" name="cert_value" required placeholder="Contoh: 6500">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ketidakpastian (±) <span class="text-danger">*</span></label>
                            <input type="number" step="0.0001" class="form-control" name="cert_u" required placeholder="Contoh: 50">
                        </div>
                    </div>
                    <p class="text-muted small mb-0"><i class="fas fa-info-circle"></i> True Value dan ketidakpastian akan menjadi acuan batas mutlak saat analis memasukkan hasil uji harian.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan ke Botol</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
