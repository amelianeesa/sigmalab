@extends('layouts.app')
@section('title', 'Detail Data - QC In-House')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item active">{{ $batch->nama_sampel }}</li>
    </x-qc-breadcrumb>

    <div class="row align-items-center mt-3 mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-0">{{ $batch->nama_sampel }}</h3>
            <div class="mt-2">
                <span class="badge bg-{{ $batch->status_color }} px-3 py-2 fs-6">{{ $batch->status_label }}</span>
            </div>
        </div>
        
        <div class="col-md-8">
            
            <div class="d-flex flex-wrap justify-content-start justify-content-md-end gap-2">
            
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

            @if($batch->status === 'pemilihan_sampel' || $batch->status === 'preparasi')
                <a href="{{ route('qc-inhouse.preparasi', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Input Data Preparasi</a>
            @elseif($batch->status === 'uji_homogenitas')
                <a href="{{ route('qc-inhouse.instruksi-homogenitas', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Lanjut Uji Homogenitas</a>
            @elseif($batch->status === 'penetapan_target')
                <a href="{{ route('qc-inhouse.penetapan-target', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Input Nilai Target</a>
            @elseif($batch->status === 'uji_stabilitas')
                <a href="{{ route('qc-inhouse.stabilitas', $batch->sampel_inhouse_id) }}" class="btn btn-primary"><i class="fas fa-play me-1"></i> Input Stabilitas</a>
            @elseif(in_array($batch->status, ['siap_digunakan', 'kadaluarsa']))
                <form action="{{ route('qc-inhouse.aktifkan', $batch->sampel_inhouse_id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button" class="btn btn-success shadow-sm" onclick="konfirmasiAktifkan(this)">
                        <i class="fas fa-power-off me-1"></i> Aktifkan Sebagai Acuan Harian
                    </button>
                </form>

                <script>
                function konfirmasiAktifkan(btn) {
                    Swal.fire({
                        title: 'Aktifkan Batch Ini?',
                        text: 'Batch yang sedang aktif sebelumnya akan otomatis dikadaluarsakan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',  // Warna hijau success
                        cancelButtonColor: '#6c757d',   // Warna abu-abu batal
                        confirmButtonText: '<i class="fas fa-check me-1"></i> Ya, Aktifkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.closest('form').submit(); // Jika klik Ya, form dikirim
                        }
                    });
                }
                </script>
            @elseif($batch->status === 'aktif')
                <a href="{{ route('qc-harian.index') }}" class="btn btn-danger shadow-sm me-2">
                    <i class="fas fa-chart-line me-1"></i> Modul Pengujian Harian (QC)
                </a>

                <form action="{{ route('qc-inhouse.nonaktifkan', $batch->sampel_inhouse_id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button" class="btn btn-outline-danger shadow-sm" onclick="konfirmasiNonaktifkan(this)">
                        <i class="fas fa-times-circle me-1"></i> Nonaktifkan Sampel
                    </button>
                </form>

                <script>
                function konfirmasiNonaktifkan(btn) {
                    Swal.fire({
                        title: 'Nonaktifkan Sampel Ini?',
                        text: 'Sampel ini akan diubah statusnya menjadi kadaluarsa dan tidak bisa digunakan untuk pengujian harian sebelum diaktifkan kembali.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-times me-1"></i> Ya, Nonaktifkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.closest('form').submit();
                        }
                    });
                }
                </script>
                
                @elseif(in_array($batch->status, ['gagal_homogenitas', 'gagal_stabilitas']))
                    @if(!$batch->akar_masalah)
                        <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInvestigasi">
                            <i class="fas fa-search me-1"></i> Lakukan Investigasi
                        </button>
                    @else
                        <form action="{{ route('qc-inhouse.preparasi-ulang', $batch->sampel_inhouse_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-warning shadow-sm fw-bold text-dark" onclick="konfirmasiPrepUlang(this)">
                                <i class="fas fa-redo-alt me-1"></i> Mulai Preparasi Ulang (Re-Prep)
                            </button>
                        </form>

                        <script>
                        function konfirmasiPrepUlang(btn) {
                            Swal.fire({
                                title: 'Mulai Preparasi Ulang?',
                                text: 'Sistem akan membuat batch baru (Revisi) dan mengarsipkan data yang lama.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ffc107',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: '<i class="fas fa-check me-1"></i> Ya, Lanjutkan!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    btn.closest('form').submit();
                                }
                            });
                        }
                        </script>
                    @endif
                @endif
        </div>
    </div>

    @if($batch->akar_masalah)
    <div class="row mb-2">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="fas fa-file-alt me-2"></i> Laporan Investigasi Kegagalan
                </div>
                <div class="card-body bg-white">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="fw-bold text-danger">Akar Masalah (Root Cause):</h6>
                            <p class="mb-0 text-dark">{{ $batch->akar_masalah }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success">Tindakan Perbaikan (Corrective Action):</h6>
                            <p class="mb-0 text-dark">{{ $batch->tindakan_perbaikan }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="small text-muted">
                        Diinvestigasi oleh: <strong>{{ $batch->investigator->username ?? '-' }}</strong> pada {{ \Carbon\Carbon::parse($batch->tanggal_investigasi)->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
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
                        <dd class="col-sm-7">{{ $batch->pembuat->username ?? '-' }} ({{ $batch->tanggal_pemilihan ? $batch->tanggal_pemilihan->format('d/m/Y') : '-' }})</dd>
                    </dl>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-3 pb-2">
                    <h5 class="fw-bold"><i class="fas id-card text-success me-2"></i>Resource Pelaksanaan Pengujian</h5>
                </div>
                <div class="card-body">
                    @foreach($batch->parameters as $p)
                        @php
                            $firstStabilitas = $p->dataStabilitas->first();
                            $firstHomogen = $p->dataHomogenitas->first();
                            $mentah = $firstStabilitas ? $firstStabilitas->data_mentah : ($firstHomogen ? $firstHomogen->data_mentah : []);
                            
                            $personilIds = $mentah['personil_ids'] ?? [];
                            $alatIds = $mentah['alat_ids'] ?? [];
                            $barangIds = $mentah['barang_ids'] ?? [];
                            $barangJumlah = $mentah['barang_jumlah'] ?? [];
                        @endphp

                        <div class="mb-3 pb-2 border-bottom">
                            <span class="fw-bold text-primary">{{ $p->parameterUji->nama_parameter }}</span>
                            <ul class="list-unstyled mb-0 small mt-1">
                                <li>
                                    <strong>Analis:</strong> 
                                    @if(count($personilIds) > 0)
                                        {{ \App\Models\Personil::whereIn('personil_id', $personilIds)->pluck('nama')->join(', ') }}
                                    @else
                                        <span class="text-muted fst-italic">Tidak tercatat</span>
                                    @endif
                                </li>
                                <li>
                                    <strong>Alat:</strong> 
                                    @if(count($alatIds) > 0)
                                        {{ \App\Models\Alat::whereIn('alat_id', $alatIds)->pluck('nama_alat')->join(', ') }}
                                    @else
                                        <span class="text-muted fst-italic">Tidak tercatat</span>
                                    @endif
                                </li>
                                <li>
                                    <strong>Bahan:</strong> 
                                    @if(count($barangIds) > 0)
                                        @php
                                            $listBarang = \App\Models\Barang::whereIn('barang_id', $barangIds)->get();
                                        @endphp
                                        <span class="text-dark">
                                            @foreach($listBarang as $bhn)
                                                {{ $bhn->nama_barang }} ({{ $barangJumlah[$bhn->barang_id] ?? 0 }} {{ $bhn->satuan }}){{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada bahan tercatat</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


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
<div class="modal fade" id="modalInvestigasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('qc-inhouse.investigasi', $batch->sampel_inhouse_id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-search me-2"></i>Form Investigasi Kegagalan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i> Sampel dinyatakan <strong>Tidak Homogen / Tidak Stabil</strong>. Sesuai instruksi kerja, sampel wajib diinvestigasi sebelum penyiapan ulang (Re-Prep) seluruh batch.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Akar Masalah (Root Cause)</label>
                        <textarea class="form-control" name="akar_masalah" rows="3" required placeholder="Jelaskan penyebab mengapa sampel gagal (misal: suhu oven tidak stabil, dll)..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tindakan Perbaikan (Corrective Action)</label>
                        <textarea class="form-control" name="tindakan_perbaikan" rows="3" required placeholder="Jelaskan perbaikan yang dilakukan sebelum sampel disiapkan ulang..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold"><i class="fas fa-save me-1"></i> Simpan Investigasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
