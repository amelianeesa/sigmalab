@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Hasil Uji</h1>
        <a href="{{ route('hasil-uji.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Hasil Uji</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Kegiatan (Kode Sampel)</th>
                            <td>{{ $hasilUji->kegiatan->kode_sampel ?? '-' }} ({{ $hasilUji->kegiatan->jenis_kegiatan ?? '-' }})</td>
                        </tr>
                        <tr>
                            <th>Parameter Uji</th>
                            <td>{{ $hasilUji->parameterUji->nama_parameter ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <td>{{ $hasilUji->parameterUji->satuan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nilai Acuan</th>
                            <td>{{ $hasilUji->parameterUji->nilai_acuan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Batas (Bawah - Atas)</th>
                            <td>{{ $hasilUji->parameterUji->batas_bawah ?? '-' }} - {{ $hasilUji->parameterUji->batas_atas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nilai Hasil</th>
                            <td class="font-weight-bold">{{ $hasilUji->nilai_hasil }}</td>
                        </tr>
                        <tr>
                            <th>Status Berketerimaan</th>
                            <td>
                                @if(strtolower($hasilUji->status_berketerimaan) == 'inlier')
                                    <span class="badge bg-success fs-6">Inlier</span>
                                @elseif(strtolower($hasilUji->status_berketerimaan) == 'outlier')
                                    <span class="badge bg-danger fs-6">Outlier</span>
                                @else
                                    <span class="badge bg-secondary fs-6">{{ $hasilUji->status_berketerimaan }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Diinput Oleh</th>
                            <td>{{ $hasilUji->pengguna->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Input</th>
                            <td>{{ $hasilUji->created_at ? $hasilUji->created_at->format('d F Y, H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if(strtolower($hasilUji->status_berketerimaan) == 'outlier')
            <div class="alert alert-warning shadow-sm" role="alert">
                <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Perhatian: Hasil Uji Outlier!</h4>
                <p>Nilai hasil uji ini berada di luar batas yang ditentukan parameter. Diperlukan tindakan lanjut.</p>
                <hr>
                @can('create', App\Models\RiwayatTindakLanjut::class)
                <a href="{{ route('tindak-lanjut.create', ['hasil_uji_id' => $hasilUji->hasil_uji_id]) }}" class="btn btn-warning">
                    <i class="fas fa-clipboard-list"></i> Catat Tindak Lanjut
                </a>
                @endcan
            </div>

            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Riwayat Tindak Lanjut</h6>
                </div>
                <div class="card-body">
                    @if(isset($hasilUji->tindakLanjut) && $hasilUji->tindakLanjut->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Tindakan</th>
                                        <th>Keterangan</th>
                                        <th>Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hasilUji->tindakLanjut as $index => $tindakLanjut)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tindakLanjut->created_at ? $tindakLanjut->created_at->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $tindakLanjut->jenis_tindakan ?? '-' }}</td>
                                        <td>{{ $tindakLanjut->keterangan ?? '-' }}</td>
                                        <td>{{ $tindakLanjut->pengguna->nama ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada riwayat tindak lanjut untuk hasil uji ini.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
