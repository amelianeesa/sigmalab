@extends('layouts.app')
@section('title', 'Daftar - crm-katalog')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item active" aria-current="page">Master Botol CRM</li>
    </x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="fas fa-certificate text-purple me-2"></i> QC CRM (Master Botol)</h2>
            <p class="text-muted mb-0 mt-1">Kelola master data botol/batch Certified Reference Material</p>
        </div>
        <div>
            <a href="{{ route('qc-crm.index') }}" class="btn btn-success shadow-sm me-2">
                <i class="fas fa-vials me-2"></i>Histori & Pengujian Harian
            </a>
            <a href="{{ route('crm-katalog.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i>Tambah Master Botol CRM
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor Lot / Botol</th>
                            <th>No. Sertifikat</th>
                            <th>Nama Produk</th>
                            <th>Produsen</th>
                            <th>Expired Date</th>
                            <th>Total Parameter</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($katalogList as $r)
                        <tr>
                            <td><span class="badge bg-primary fs-6">{{ $r->nomor_lot }}</span></td>
                            <td>{{ $r->nomor_sertifikat ?? '-' }}</td>
                            <td class="fw-bold">{{ $r->nama_produk }}</td>
                            <td>{{ $r->produsen ?? '-' }}</td>
                            <td>
                                @if($r->tanggal_expired)
                                    @if($r->tanggal_expired < now())
                                        <span class="text-danger fw-bold"><i class="fas fa-exclamation-circle"></i> {{ $r->tanggal_expired->format('d M Y') }} (Expired)</span>
                                    @else
                                        {{ $r->tanggal_expired->format('d M Y') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $r->sertifikats_count }} Parameter</span></td>
                            <td>
                                @if($r->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Inaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('crm-katalog.show', $r->id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i> Detail & Sertifikat
                                </a>
                                <button class="btn btn-sm btn-outline-secondary ms-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $r->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $r->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('crm-katalog.update', $r->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Botol: {{ $r->nomor_lot }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Lot / Kode Botol <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="nomor_lot" value="{{ $r->nomor_lot }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Sertifikat</label>
                                                <input type="text" class="form-control" name="nomor_sertifikat" value="{{ $r->nomor_sertifikat }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="nama_produk" value="{{ $r->nama_produk }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Produsen</label>
                                                <input type="text" class="form-control" name="produsen" value="{{ $r->produsen }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Expired</label>
                                                <input type="date" class="form-control" name="tanggal_expired" value="{{ $r->tanggal_expired ? $r->tanggal_expired->format('Y-m-d') : '' }}">
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="activeSwitch{{ $r->id }}" {{ $r->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="activeSwitch{{ $r->id }}">Botol Aktif (Bisa digunakan analis)</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data botol CRM.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('crm-katalog.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Botol CRM Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomor Lot / Kode Botol <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nomor_lot" required placeholder="Contoh: CRM-COAL-001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_produk" required placeholder="Contoh: Coal Reference Material">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Produsen</label>
                        <input type="text" class="form-control" name="produsen" placeholder="Nama instansi penerbit">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Expired</label>
                        <input type="date" class="form-control" name="tanggal_expired">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Botol</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
