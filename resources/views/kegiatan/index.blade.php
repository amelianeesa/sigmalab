@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">Daftar Kegiatan</h1>
            @can('create', App\Models\Kegiatan::class)
                <a href="{{ route('kegiatan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Kegiatan
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Kegiatan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('kegiatan.index') }}" method="GET" class="row g-3 live-search-form" data-target="#table-container">
                <div class="col-md-4">
                    <label for="kode_sampel" class="form-label">Kode Sampel</label>
                    <input type="text" class="form-control" id="kode_sampel" name="kode_sampel" value="{{ request('kode_sampel') }}">
                </div>
                <div class="col-md-3">
                    <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                    <select class="form-select" id="jenis_kegiatan" name="jenis_kegiatan">
                        <option value="">Semua</option>
                        <option value="pengujian" {{ request('jenis_kegiatan') == 'pengujian' ? 'selected' : '' }}>Pengujian</option>
                        <option value="kalibrasi" {{ request('jenis_kegiatan') == 'kalibrasi' ? 'selected' : '' }}>Kalibrasi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status_kegiatan" class="form-label">Status</label>
                    <select class="form-select" id="status_kegiatan" name="status_kegiatan">
                        <option value="">Semua</option>
                        <option value="draft" {{ request('status_kegiatan') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="berjalan" {{ request('status_kegiatan') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status_kegiatan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status_kegiatan') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-sync-alt"></i> Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body" id="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th>Nama Kegiatan</th>
                            <th>Kode Sampel</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-center">Jumlah Alat</th>
                            <th class="text-center">Jumlah Personil</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatans as $kegiatan)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($kegiatans->currentPage() - 1) * $kegiatans->perPage() }}</td>
                                <td>
                                    <span class="fw-bold">{{ $kegiatan->nama_kegiatan }}</span><br>
                                    <small class="text-muted">{{ ucfirst($kegiatan->jenis_kegiatan) }}</small>
                                </td>
                                <td>{{ $kegiatan->kode_sampel ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y') }}</td>
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
                                <td>{{ $kegiatan->pembuatKegiatan ? $kegiatan->pembuatKegiatan->username : '-' }}</td>
                                <td class="text-center">{{ $kegiatan->alatDigunakan->count() }}</td>
                                <td class="text-center">{{ $kegiatan->personilTerlibat->count() }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kegiatan.show', $kegiatan->kegiatan_id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @can('update', $kegiatan)
                                    <a href="{{ route('kegiatan.edit', $kegiatan->kegiatan_id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('delete', $kegiatan)
                                    <form action="{{ route('kegiatan.destroy', $kegiatan->kegiatan_id) }}" method="POST" class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Data kegiatan tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $kegiatans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data kegiatan yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
