@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">Competency Matrix</h2>
            <p class="text-muted mb-0">Ringkasan status sertifikasi &amp; pelatihan tiap personil aktif, per jenis sertifikasi.</p>
        </div>
        <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Personil
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-exclamation-circle me-1"></i> Segera Berakhir (≤60 hari)</span>
                <span class="badge bg-danger text-white px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i> Kedaluwarsa</span>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="bi bi-dash-circle me-1"></i> Belum Pernah</span>
            </div>
            <form method="GET" action="{{ route('sdm.competency-matrix') }}" class="d-flex align-items-center gap-2">
                <select name="kategori" class="form-select form-select-sm" style="min-width: 170px;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $value => $label)
                        <option value="{{ $value }}" {{ $kategori === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <a href="{{ route('sdm.competency-matrix.pdf', array_filter(['kategori' => $kategori])) }}" class="btn btn-outline-danger btn-sm px-3 fw-semibold text-nowrap">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                </a>
            </form>
        </div>
        <div class="card-body px-4 pb-4">
            @if($jenisSertifikasiList->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-grid-3x3-gap fs-2 d-block mb-2"></i>
                    Belum ada data sertifikasi yang tercatat untuk membentuk matriks kompetensi.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0" style="font-size: 0.85rem;">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th class="text-start" style="min-width: 200px;">Nama Personil</th>
                                @foreach($jenisSertifikasiList as $jenis)
                                    <th style="min-width: 150px;">{{ $jenis }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matrix as $row)
                                <tr>
                                    <td class="text-start fw-bold">
                                        <a href="{{ route('sdm.kompetensi.detail', $row['personil']->personil_id) }}" class="text-decoration-none">
                                            {{ $row['personil']->nama }}
                                        </a>
                                        <div class="text-muted small fw-normal">{{ $row['personil']->jabatan }}</div>
                                    </td>
                                    @foreach($jenisSertifikasiList as $jenis)
                                        @php $cell = $row['kompetensi'][$jenis]; @endphp
                                        <td>
                                            @if($cell)
                                                <a href="{{ route('sdm.kompetensi.detail', $row['personil']->personil_id) }}" class="text-decoration-none" title="Berlaku s.d {{ $cell['kompetensi']->tanggal_berakhir?->format('d-m-Y') ?? 'Tidak Terbatas' }}">
                                                    <span class="badge {{ $cell['status']['class'] }} px-2 py-1 rounded-pill">
                                                        <i class="bi bi-{{ $cell['status']['icon'] }} me-1"></i>{{ $cell['status']['label'] }}
                                                    </span>
                                                </a>
                                            @else
                                                <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">
                                                    <i class="bi bi-dash-circle me-1"></i>Belum Pernah
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $jenisSertifikasiList->count() + 1 }}" class="text-center text-muted py-4">
                                        Belum ada data personil aktif yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection