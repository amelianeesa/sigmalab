@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Instruksi Homogenitas</li>
    </x-qc-breadcrumb>

    <div class="row justify-content-center mt-3">
        <div class="col-xl-9">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-4 border-bottom-0 text-center">
                    <h3 class="fw-bold text-dark"><i class="fas fa-list-ol text-primary me-2"></i>Instruksi Pelaksanaan Uji Homogenitas</h3>
                    <p class="text-muted">Harap cetak dan patuhi urutan pengujian di bawah ini untuk menjaga validitas statistik uji.</p>
                </div>
                
                <div class="card-body p-4">
                    <div class="row g-4 mt-1">
                        <div class="col-12">
                            <div class="p-3 border rounded bg-light">
                                <h5 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-random me-2"></i>1. Urutan Pengambilan Botol</h5>
                                <p class="small text-muted mb-2">Ambil botol dari kotak penyimpanan (isi total 50 botol) persis sesuai urutan acak 10 angka di bawah ini:</p>
                                <table class="table table-sm table-bordered text-center bg-white mt-3">
                                    <thead class="table-primary">
                                        <tr>
                                            <th width="50%">Urutan Ambil</th>
                                            <th width="50%">Nomor Botol yang Diambil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tabelAcak as $acak)
                                            <tr>
                                                <td class="fw-bold">Ke - {{ $acak->urutan }}</td>
                                                <td class="fs-5 fw-bold text-danger">{{ $acak->nomor_botol }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-4 shadow-sm border-0">
                        <i class="fas fa-exclamation-triangle me-2"></i> <strong>Perhatian Analis:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Gunakan parameter uji yang telah disetujui: 
                                <strong>
                                @foreach($batch->parameters as $p)
                                    {{ $p->parameterUji->nama_parameter }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                                </strong>
                            </li>
                            <li>Metode acuan yang digunakan: <strong>{{ strtoupper($batch->metode_acuan) }}</strong></li>
                        </ul>
                    </div>

                </div>
                <div class="card-footer bg-white text-center py-3">
                    <button onclick="window.print()" class="btn btn-outline-secondary me-2"><i class="fas fa-print me-1"></i> Cetak Instruksi</button>
                    <a href="{{ route('qc-inhouse.homogenitas', $batch->sampel_inhouse_id) }}?v={{ time() }}" class="btn btn-primary px-4"><i class="fas fa-arrow-right me-1"></i> Lanjut Input Data Homogenitas</a>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
@media print {
    body * { visibility: hidden; }
    .card * { visibility: visible; }
    .card { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important;}
    .card-footer, .breadcrumb { display: none !important; }
}
</style>
@endsection
