@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Parameter Uji</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Parameter Uji</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                    <table class="table table-hover table-striped align-middle border" style="font-size: 0.85rem;">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Parameter</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Acuan</th>
                                <th class="text-center">Range Batas (Min - Max)</th>
                                <th>Metode/Kriteria</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parameterUji as $index => $item)
                            <tr>
                                <td class="text-center">{{ $parameterUji->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $item->nama_parameter }}</td>
                                <td class="text-center">{{ $item->satuan }}</td>
                                <td class="text-center">{{ number_format($item->nilai_acuan, 2) }}</td>
                                <td class="text-center">{{ number_format($item->batas_bawah, 2) }} - {{ number_format($item->batas_atas, 2) }}</td>
                                <td>{{ $item->metode_kriteria ?? '-' }}</td>
                                <td class="text-center">
                                    @if($item->status_aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="{{ route('parameter-uji.show', $item->parameter_uji_id) }}" class="btn btn-info btn-sm text-white" title="Detail"><i class="fas fa-eye"></i></a>
                                    
                                    @can('update', $item)
                                        <a href="{{ route('parameter-uji.edit', $item->parameter_uji_id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    @endcan

                                    @can('delete', $item)
                                        <form action="{{ route('parameter-uji.destroy', $item->parameter_uji_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus / Nonaktifkan" onclick="confirmDelete(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Data parameter uji tidak ditemukan.</td>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(button) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: "Jika parameter uji sudah digunakan di Hasil Uji, maka hanya akan di-nonaktifkan. Lanjutkan?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endsection
