@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h3>Matriks Kompetensi & Sertifikasi</h3>
                    <p class="text-muted mb-0">Detail sertifikasi yang dimiliki oleh <strong>{{ $personil->nama }}</strong> ({{ $personil->jabatan }})</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Sertifikasi</th>
                                <th>No. Sertifikat</th>
                                <th>Tanggal Terbit</th>
                                <th>Tanggal Berakhir</th>
                                <th>Status Masa Berlaku</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($personil->kompetensi ?? [] as $komp)
                            <tr>
                                <td>{{ $komp->jenis_sertifikasi }}</td>
                                <td>{{ $komp->no_sertifikasi }}</td>
                                <td>{{ $komp->tanggal_terbit }}</td>
                                <td>{{ $komp->tanggal_berakhir }}</td>
                                <td>
                                    @if(strtotime($komp->tanggal_berakhir) >= time())
                                        <span class="badge bg-success">Aktif / Berlaku</span>
                                    @else
                                        <span class="badge bg-danger">Expired / Habis Masa Berlaku</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data sertifikasi kompetensi untuk personil ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <a href="{{ route('sdm.index') }}" class="btn btn-secondary btn-sm mt-3">Kembali ke Daftar Personil</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection