@extends('layouts.app')
@section('title', 'Daftar - Parameter Uji')

@section('content')
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item active">Parameter Uji</li>
    </ol>
    <h1 class="mb-4">Parameter Uji</h1>



    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-vial me-2"></i>Data Master Parameter Uji</h6>
            @can('create', App\Models\ParameterUji::class)
                <a href="{{ route('parameter-uji.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Parameter</a>
            @endcan
        </div>
        <div class="card-body">
            <form action="{{ route('parameter-uji.index') }}" method="GET" class="row g-2 mb-3 align-items-center live-search-form" data-target="#table-container">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari Nama Parameter..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="filter_status" class="form-select form-select-sm">
                        <option value="semua" {{ $filterStatus === 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="aktif" {{ $filterStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $filterStatus === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync-alt"></i> Reset</a>
                </div>
            </form>

            <div id="table-container">
                <div class="table-responsive">
                                        <table class="table table-hover table-striped align-middle border" style="font-size: 0.9rem;">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th style="width: 130px;">Kode Parameter</th>
                                <th>Nama Parameter</th>
                                <th class="text-center" style="width: 100px;">Satuan</th>
                                <th class="text-center" style="width: 120px;">Status</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parameterUji as $index => $item)
                            <tr>
                                <td class="text-center">{{ $parameterUji->firstItem() + $index }}</td>
                                <td class="fw-bold text-primary fs-6">
                                    {{ strtoupper($item->nama_parameter) }}
                                </td>
                                <td class="fw-semibold text-dark">
                                    {{ $item->nama_lengkap }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $item->satuan ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->status_aktif)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Aktif</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    
                                    @can('update', $item)
                                        <a href="{{ route('parameter-uji.edit', $item->parameter_uji_id) }}" class="btn btn-warning btn-sm shadow-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    @endcan

                                    @can('delete', $item)
                                        <form action="{{ route('parameter-uji.destroy', $item->parameter_uji_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm shadow-sm" title="Hapus / Nonaktifkan" data-confirm-delete="Jika parameter uji sudah digunakan di Hasil Uji, maka hanya akan di-nonaktifkan. Lanjutkan?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Data parameter uji tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $parameterUji->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
