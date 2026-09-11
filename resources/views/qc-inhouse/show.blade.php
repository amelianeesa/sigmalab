@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item active">{{ $batch->nama_sampel }}</li>
    </x-qc-breadcrumb>

    <!-- Header Status -->
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">{{ $batch->nama_sampel }}</h3>
            <span class="badge bg-{{ $batch->status_color }} mt-2 px-3 py-2 fs-6">{{ $batch->status_label }}</span>
        </div>
        <div class="d-flex gap-2">
            <!-- Tombol Lihat Data (Masa Lalu) -->
            @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi']))
                <a href="{{ route('qc-inhouse.preparasi', $batch->sampel_inhouse_id) }}" class="btn btn-outline-secondary"><i class="fas fa-eye me-1"></i> Preparasi</a>
            @endif
            
            @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi', 'uji_homogenitas']))
                <a href="{{ route('qc-inhouse.homogenitas', $batch->sampel_inhouse_id) }}" class="btn btn-outline-secondary"><i class="fas fa-eye me-1"></i> Homogenitas</a>
            @endif

            @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi', 'uji_homogenitas', 'gagal_homogenitas', 'penetapan_target']))
                <a href="{{ route('qc-inhouse.penetapan-target', $batch->sampel_inhouse_id) }}" class="btn btn-outline-secondary"><i class="fas fa-eye me-1"></i> Target</a>
            @endif

            @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi', 'uji_homogenitas', 'gagal_homogenitas', 'penetapan_target']))
                <a href="{{ route('qc-inhouse.stabilitas', $batch->sampel_inhouse_id) }}" class="btn btn-outline-secondary"><i class="fas fa-eye me-1"></i> Stabilitas</a>
            @endif

            <!-- Tombol Aksi Utama (Saat Ini) -->
            @if($batch->status === 'pemilihan_sampel' || $batch->status === 'preparasi')
                <a href="{{ route('qc-inhouse.preparasi', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Input Data Preparasi</a>
            @elseif($batch->status === 'uji_homogenitas')
                <a href="{{ route('qc-inhouse.instruksi-homogenitas', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Lanjut Uji Homogenitas</a>
            @elseif($batch->status === 'penetapan_target')
                <a href="{{ route('qc-inhouse.penetapan-target', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Input Nilai Target</a>
            @elseif($batch->status === 'uji_stabilitas')
                <a href="{{ route('qc-inhouse.stabilitas', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Input Stabilitas</a>
            @elseif($batch->status === 'siap_digunakan')
                <form action="{{ route('qc-inhouse.aktifkan', $batch->sampel_inhouse_id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm" onclick="return confirm('Aktifkan batch ini? Batch yang sedang aktif sebelumnya akan otomatis dikadaluarsakan.')">
                        <i class="fas fa-power-off me-1"></i> Aktifkan Sebagai Acuan Harian
                    </button>
                </form>
            @elseif($batch->status === 'aktif')
                <a href="{{ route('qc-harian.index') }}" class="btn btn-danger shadow-sm"><i class="fas fa-chart-line me-1"></i> Modul Pengujian Harian (QC)</a>
            @elseif(in_array($batch->status, ['gagal_homogenitas', 'gagal_stabilitas']))
                <button class="btn btn-danger"><i class="fas fa-search me-1"></i> Lakukan Investigasi</button>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Info Umum -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-white pt-3 pb-2">
                    <h5 class="fw-bold"><i class="fas fa-info-circle text-info me-2"></i>Informasi Umum</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Kode Batch</dt>
                        <dd class="col-sm-7 fw-bold">{{ $batch->kode_batch ?? '-' }}</dd>
                        
                        <dt class="col-sm-5 text-muted">Jenis Batubara</dt>
                        <dd class="col-sm-7 text-uppercase">{{ str_replace('_', ' ', $batch->jenis_batubara) }}</dd>
                        
                        <dt class="col-sm-5 text-muted">Metode Acuan</dt>
                        <dd class="col-sm-7 text-uppercase">{{ $batch->metode_acuan ?? '-' }}</dd>
                        
                        <dt class="col-sm-5 text-muted">Jumlah Botol</dt>
                        <dd class="col-sm-7">{{ $batch->jumlah_botol ? $batch->jumlah_botol . ' botol' : '-' }}</dd>

                        <dt class="col-sm-5 text-muted">Dibuat Oleh</dt>
                        <dd class="col-sm-7">{{ $batch->pembuat->name ?? '-' }} ({{ $batch->tanggal_pemilihan ? $batch->tanggal_pemilihan->format('d/m/Y') : '-' }})</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Kolom Parameter Status -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-white pt-3 pb-2">
                    <h5 class="fw-bold"><i class="fas fa-tasks text-primary me-2"></i>Status Per Parameter Uji</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Parameter</th>
                                    <th>Status Homogenitas</th>
                                    <th>Status Target</th>
                                    <th>Status Stabilitas</th>
                                    <th>Keputusan Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batch->parameters as $p)
                                    <tr>
                                        <td class="fw-bold text-start ps-3">{{ $p->parameterUji->nama_parameter }}</td>
                                        
                                        <td>
                                            @if($p->status_parameter === 'draft' && !$p->f_hitung)
                                                <span class="badge bg-secondary">Belum Uji</span>
                                            @elseif(in_array($p->status_parameter, ['homogen', 'target_set', 'stabil']))
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Homogen</span><br>
                                                <small class="text-muted">F = {{ number_format($p->f_hitung, 3) }}</small>
                                            @elseif($p->status_parameter === 'tidak_homogen')
                                                <span class="badge bg-danger"><i class="fas fa-times"></i> Tidak Homogen</span>
                                            @else
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Homogen</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($p->mean_target)
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Selesai</span><br>
                                                <small class="text-muted">Target: {{ number_format($p->mean_target, 3) }}</small>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($p->status_parameter === 'stabil')
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Stabil</span><br>
                                                <small class="text-muted">t = {{ number_format($p->t_hitung, 3) }}</small>
                                            @elseif($p->status_parameter === 'tidak_stabil')
                                                <span class="badge bg-danger"><i class="fas fa-times"></i> Tidak Stabil</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($p->status_parameter === 'stabil')
                                                <span class="badge bg-success px-3 py-2">AKTIF</span>
                                            @elseif(in_array($p->status_parameter, ['tidak_homogen', 'tidak_stabil']))
                                                <span class="badge bg-danger px-3 py-2">GAGAL</span>
                                            @else
                                                <span class="badge bg-warning text-dark px-3 py-2">PROSES</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
