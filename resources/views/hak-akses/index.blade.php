@extends('layouts.app')

@section('content')
<style>
    .table thead th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        padding: 8px 6px;
        font-size: 0.7rem;
        text-align: center;
        vertical-align: middle;
    }
    .table tbody td {
        padding: 8px 6px;
        font-size: 0.73rem;
        vertical-align: middle;
    }
</style>

<div class="container-fluid px-3 pt-1 pb-2" style="font-size: 0.78rem;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2 px-1">
        <div>
            <nav aria-label="breadcrumb">

            </nav>
            <h4 class="fw-bold mb-0">Manajemen Hak Akses</h4>
            <p class="text-muted mb-0" style="font-size: 0.72rem;">Atur kewenangan setiap Role terhadap masing-masing Modul sistem secara dinamis.</p>
        </div>
    </div>

    <div class="alert alert-info py-2 px-3 mb-2 shadow-sm d-flex align-items-center" role="alert" style="font-size: 0.73rem;">
        <i class="fas fa-info-circle me-2 fs-6"></i>
        <div>Perubahan matriks ini akan langsung memengaruhi menu apa saja yang tampil di sidebar dan hak akses operasi (Create, Read, Update, Delete) masing-masing jabatan.</div>
    </div>

    <div class="mb-2 px-1">
        <form action="{{ route('hak-akses.update') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0 bg-white shadow-sm rounded-3">
                    <thead>
                        <tr>
                            <th class="text-start" style="min-width: 180px; position: sticky; left: 0; z-index: 2;">Role \ Modul</th>
                            @foreach($modules as $modul)
                                <th style="min-width: 140px;">
                                    {{ $modul->nama_modul }}<br>
                                    <span class="fw-normal" style="font-size: 0.62rem; opacity: 0.85;">({{ $modul->modul_id }})</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td class="fw-bold text-dark bg-white" style="position: sticky; left: 0; z-index: 1; font-size: 0.73rem; padding: 8px 6px;">
                                    {{ $role->nama_role }}
                                </td>
                                @foreach($modules as $modul)
                                    @php
                                        $currentLevel = $matrix[$role->roles_id][$modul->modul_id] ?? 'none';
                                    @endphp
                                    <td class="text-center" style="padding: 8px 6px;">
                                        <select name="matrix[{{ $role->roles_id }}][{{ $modul->modul_id }}]" class="form-select form-select-sm py-1 {{ $currentLevel != 'none' ? 'border-primary' : '' }}" style="font-size: 0.73rem;">
                                            <option value="none" {{ $currentLevel == 'none' ? 'selected' : '' }} class="text-muted">Tidak Ada Akses</option>
                                            <option value="lihat" {{ $currentLevel == 'lihat' ? 'selected' : '' }}>Lihat Saja</option>
                                            <option value="tambah_ubah" {{ $currentLevel == 'tambah_ubah' ? 'selected' : '' }}>Tambah/Ubah</option>
                                            <option value="full" {{ $currentLevel == 'full' ? 'selected' : '' }} class="text-danger fw-bold">Akses Penuh</option>
                                        </select>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end px-1 d-flex justify-content-end gap-2">
                <a href="{{ route('sdm.index') }}" class="btn btn-secondary btn-sm px-3 py-1 fw-semibold rounded-2" style="font-size: 0.73rem;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-sm text-white px-3 py-1 fw-semibold rounded-2" style="background-color: #1b3152; font-size: 0.73rem;">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection