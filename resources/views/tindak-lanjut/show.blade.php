@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Tindak Lanjut</h1>
        <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Tindak Lanjut #{{ $tindakLanjut->riwayat_tindak_lanjut_id }}</h6>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 250px;">Referensi Hasil Uji ID</th>
                    <td>: {{ $tindakLanjut->hasil_uji_id }}</td>
                </tr>
                <tr>
                    <th>Parameter Uji</th>
                    <td>: {{ $tindakLanjut->hasilUji->parameterUji->nama_parameter ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kegiatan (Kode Sampel)</th>
                    <td>: {{ $tindakLanjut->hasilUji->kegiatan->kode_sampel ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nilai Hasil Uji</th>
                    <td>: {{ $tindakLanjut->hasilUji->nilai_hasil ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status Keberterimaan</th>
                    <td>: 
                        @if(($tindakLanjut->hasilUji->status_berketerimaan ?? '') == 'inlier')
                            <span class="badge bg-success">Inlier</span>
                        @elseif(($tindakLanjut->hasilUji->status_berketerimaan ?? '') == 'outlier')
                            <span class="badge bg-danger">Outlier</span>
                        @else
                            {{ $tindakLanjut->hasilUji->status_berketerimaan ?? '-' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status Tindak Lanjut</th>
                    <td>: 
                        @if($tindakLanjut->status_tindak_lanjut == 'belum_ditindaklanjuti')
                            <span class="badge bg-warning">Belum Ditindaklanjuti</span>
                        @elseif($tindakLanjut->status_tindak_lanjut == 'dalam_investigasi')
                            <span class="badge bg-info">Dalam Investigasi</span>
                        @elseif($tindakLanjut->status_tindak_lanjut == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            {{ $tindakLanjut->status_tindak_lanjut }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Catatan Investigasi</th>
                    <td>: <br><div class="mt-2 p-3 bg-light border rounded">{{ $tindakLanjut->catatan_investigasi }}</div></td>
                </tr>
                <tr>
                    <th>Ditindaklanjuti Oleh</th>
                    <td>: {{ $tindakLanjut->ditindaklanjutiOleh->nama_pengguna ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Dibuat</th>
                    <td>: {{ $tindakLanjut->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
