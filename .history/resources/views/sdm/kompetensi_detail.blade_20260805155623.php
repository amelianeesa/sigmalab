@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">{{ $personil->nama }}</h3>
                <p class="mb-0 opacity-75">{{ $personil->jabatan }} — Unit Kerja: {{ $personil->unit_kerja }} | No. Induk: {{ $personil->no_induk }}</p>
            </div>
            <div>
                <a href="{{ route('sdm.index') }}" class="btn btn-light text-primary btn-sm px-3 fw-semibold">Kembali</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="fw-bold text-dark mb-0">Matriks Kompetensi & Sertifikasi Personil</h5>
            <p class="text-muted small">Riwayat sertifikasi keahlian yang terdaftar secara resmi.</p>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3">Jenis Sertifikasi</th>
                            <th class="py-3">No. Sertifikat</th>
                            <th class="py-3">Tanggal Terbit</th>
                            <th class="py-3">Masa Berlaku Berakhir</th>
                            <th class="py-3 text-center">Status Keaktifan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil->kompetensi ?? [] as $komp)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $komp->jenis_sertifikasi }}</td>
                            <td><code>{{ $komp->no_sertifikasi }}</code></td>
                            <td>{{ date('d-m-Y', strtotime($komp->tanggal_terbit)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($komp->tanggal_berakhir)) }}</td>
                            <td class="text-center">
                                @if(strtotime($komp->tanggal_berakhir) >= time())
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif / Berlaku</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Masa Berlaku Habis</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-exclamation fs-2 d-block mb-2"></i>
                                Belum ada data sertifikasi kompetensi yang tercatat untuk personil ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection