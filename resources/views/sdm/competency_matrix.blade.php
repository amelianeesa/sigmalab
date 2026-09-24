@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 pt-1 pb-2" style="font-size: 0.78rem;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2 px-1">
        <div>
            <h4 class="fw-bold mb-0">Competency Matrix</h4>
            <p class="text-muted mb-0" style="font-size: 0.72rem;">Lihat status personil untuk satu jenis sertifikasi dalam satu waktu.</p>
            <form method="GET" action="{{ route('sdm.competency-matrix') }}" class="d-flex align-items-center flex-wrap gap-2 mt-2">
                <select name="kategori" class="form-select form-select-sm py-1" style="width: 160px; font-size: 0.73rem;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $value => $label)
                        <option value="{{ $value }}" {{ $kategori === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="sertifikasi" class="form-select form-select-sm py-1" style="width: 220px; font-size: 0.73rem;" onchange="this.form.submit()">
                    <option value="">Pilih Sertifikasi</option>
                    @foreach($jenisSertifikasiOptions as $item)
                        <option value="{{ $item }}" {{ $jenisSertifikasi === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
                @if($jenisSertifikasi)
                    <a href="{{ route('sdm.competency-matrix.pdf', array_filter(['kategori' => $kategori, 'sertifikasi' => $jenisSertifikasi])) }}" class="btn btn-outline-danger btn-sm px-2.5 py-1 fw-semibold text-nowrap rounded-pill" style="font-size: 0.73rem;">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                    </a>
                @endif
            </form>
        </div>
        <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1 fw-semibold rounded-pill me-1" style="font-size: 0.73rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Personil
        </a>
    </div>

    <div>
        @if(!$jenisSertifikasi)
            <div class="text-center py-4 text-muted" style="font-size: 0.75rem;">
                <i class="bi bi-funnel fs-4 d-block mb-1"></i>
                Pilih jenis sertifikasi untuk melihat status tiap personil.
            </div>
        @else
            <div class="text-center text-muted py-1 mb-2" style="font-size: 0.73rem;">
                Menampilkan status sertifikasi: <span class="fw-semibold text-dark">{{ $jenisSertifikasi }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0 bg-white shadow-sm rounded-3">
                    <thead>
                        <tr>
                            <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: left; vertical-align: middle; min-width: 200px;">Nama Personil</th>
                            <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle; width: 160px;">Status Sertifikasi</th>
                            <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle; width: 130px;">Berlaku Sampai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matrix as $row)
                            <tr>
                                <td class="text-start fw-bold" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">
                                    <a href="{{ route('sdm.kompetensi.detail', $row['personil']->personil_id) }}" class="text-decoration-none text-dark">
                                        {{ $row['personil']->nama }}
                                    </a>
                                    <div class="text-muted fw-normal" style="font-size: 0.65rem;">{{ $row['personil']->jabatan }}</div>
                                </td>
                                <td class="text-center" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">
                                    @if($row['kompetensi'])
                                        <a href="{{ route('sdm.kompetensi.detail', $row['personil']->personil_id) }}" class="text-decoration-none">
                                            <span class="badge {{ $row['status']['class'] }} px-2 py-1.5 text-nowrap" style="font-size: 0.65rem;"><i class="bi bi-{{ $row['status']['icon'] }} me-1"></i>{{ $row['status']['label'] }}</span>
                                        </a>
                                    @else
                                        <span class="badge bg-light text-dark border px-2 py-1.5 text-nowrap" style="font-size: 0.65rem;"><i class="bi bi-dash-circle me-1"></i>Belum Pernah</span>
                                    @endif
                                </td>
                                <td class="text-center" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">{{ $row['kompetensi']?->tanggal_berakhir?->format('d-m-Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4" style="font-size: 0.73rem;">
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
@endsection