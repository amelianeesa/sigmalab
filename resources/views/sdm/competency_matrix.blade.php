@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">Competency Matrix</h2>
            <p class="text-muted mb-0">Lihat status personil untuk satu jenis sertifikasi dalam satu waktu.</p>
            <form method="GET" action="{{ route('sdm.competency-matrix') }}" class="d-flex align-items-center flex-wrap gap-2 mt-3">
                <select name="kategori" class="form-select form-select-sm" style="width: 180px;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $value => $label)
                        <option value="{{ $value }}" {{ $kategori === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="sertifikasi" class="form-select form-select-sm" style="width: 250px;" onchange="this.form.submit()">
                    <option value="">Pilih Sertifikasi</option>
                    @foreach($jenisSertifikasiOptions as $item)
                        <option value="{{ $item }}" {{ $jenisSertifikasi === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
                @if($jenisSertifikasi)
                    <a href="{{ route('sdm.competency-matrix.pdf', array_filter(['kategori' => $kategori, 'sertifikasi' => $jenisSertifikasi])) }}" class="btn btn-outline-danger btn-sm px-3 fw-semibold text-nowrap">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                    </a>
                @endif
            </form>
        </div>
        <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Personil
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body px-4 pb-4">
            @if(!$jenisSertifikasi)
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-funnel fs-2 d-block mb-2"></i>
                    Pilih jenis sertifikasi untuk melihat status tiap personil.
                </div>
            @else
                <div class="text-center text-muted py-2 mb-2">
                    Menampilkan status sertifikasi: <span class="fw-semibold text-dark">{{ $jenisSertifikasi }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th class="text-start" style="min-width: 200px;">Nama Personil</th>
                                <th>Status Sertifikasi</th>
                                <th>Berlaku Sampai</th>
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
                                    <td>
                                        @if($row['kompetensi'])
                                            <a href="{{ route('sdm.kompetensi.detail', $row['personil']->personil_id) }}" class="text-decoration-none">
                                                <span class="badge {{ $row['status']['class'] }} px-2 py-1 rounded-pill"><i class="bi bi-{{ $row['status']['icon'] }} me-1"></i>{{ $row['status']['label'] }}</span>
                                            </a>
                                        @else
                                            <span class="badge bg-light text-dark border px-2 py-1 rounded-pill"><i class="bi bi-dash-circle me-1"></i>Belum Pernah</span>
                                        @endif
                                    </td>
                                    <td>{{ $row['kompetensi']?->tanggal_berakhir?->format('d-m-Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
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
