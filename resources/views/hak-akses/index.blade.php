@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen Hak Akses</h2>
            <p class="text-muted mb-0">Atur kewenangan setiap Role terhadap masing-masing Modul sistem secara dinamis.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="alert alert-info py-2" style="font-size: 0.9rem;">
                <i class="fas fa-info-circle me-1"></i> Perubahan matriks ini akan langsung memengaruhi menu apa saja yang tampil di sidebar dan hak akses operasi (Create, Read, Update, Delete) masing-masing jabatan.
            </div>

            <form action="{{ route('hak-akses.update') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th class="text-start" style="min-width: 200px; position: sticky; left: 0; background: #f8f9fa; z-index: 2;">Role \ Modul</th>
                                @foreach($modules as $modul)
                                    <th style="min-width: 150px;">
                                        {{ $modul->nama_modul }}<br>
                                        <small class="text-muted fw-normal">({{ $modul->modul_id }})</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td class="fw-bold bg-light" style="position: sticky; left: 0; z-index: 1;">
                                        {{ $role->nama_role }}
                                    </td>
                                    @foreach($modules as $modul)
                                        @php
                                            $currentLevel = $matrix[$role->roles_id][$modul->modul_id] ?? 'none';
                                        @endphp
                                        <td class="text-center p-2">
                                            <select name="matrix[{{ $role->roles_id }}][{{ $modul->modul_id }}]" class="form-select form-select-sm {{ $currentLevel != 'none' ? 'border-primary' : '' }}">
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

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i> Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
