@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('verifikasi-mutu.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('qc-harian.index') }}" class="text-decoration-none">Pengujian Harian QC</a></li>
        <li class="breadcrumb-item active">Investigasi OOC</li>
    </ol>
    <h2 class="mb-4 fw-bold text-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>Tindakan Perbaikan (CAPA) Out of Control
    </h2>

    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 border-top border-danger border-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Detail Pengujian Outlier</h5>
                    
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted w-50">Parameter</td>
                            <td class="fw-bold">{{ strtoupper($qc->parameterUji->nama_parameter) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Pengujian</td>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($qc->tanggal_uji)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Analis</td>
                            <td class="fw-bold">{{ $qc->analis ? $qc->analis->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Simplo (D1)</td>
                            <td class="fw-bold">{{ number_format($qc->nilai_d1, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Duplo (D2)</td>
                            <td class="fw-bold">{{ $qc->nilai_d2 ? number_format($qc->nilai_d2, 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Akhir Rata-rata</td>
                            <td class="fw-bold text-danger fs-5">{{ number_format($qc->nilai_akhir, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pelanggaran Rule</td>
                            <td><span class="badge bg-danger text-wrap text-start lh-base">{{ $qc->pelanggaran_rule }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-info-circle me-2"></i>Aturan Penguncian Sistem</h6>
                    <p class="small text-muted mb-0">
                        Sistem telah <strong>membekukan sementara</strong> fasilitas input data pengujian harian untuk parameter <strong>{{ strtoupper($qc->parameterUji->nama_parameter) }}</strong>. Anda maupun analis lain tidak dapat menambahkan data QC untuk parameter ini hingga Root Cause Analysis (Akar Masalah) dan Tindakan Perbaikan (Corrective Action) dilaporkan pada form di samping.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Laporan Tindakan Perbaikan (CAPA)</h5>
                    
                    <form action="{{ route('qc-harian.investigasi.store', $qc->id) }}" method="POST" id="formCapa">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Analisis Akar Masalah & Tindakan Perbaikan <span class="text-danger">*</span></label>
                            <p class="small text-muted mb-2">Jelaskan mengapa hasil pengujian menjadi outlier (misal: kalibrasi alat bergeser, reagen kadaluarsa, kesalahan preparasi) dan apa tindakan perbaikannya (misal: kalibrasi ulang, ganti reagen, re-test).</p>
                            <textarea name="catatan_investigasi" id="catatan_investigasi" rows="8" class="form-control" required minlength="10" placeholder="Contoh: Terjadi kesalahan penimbangan pada botol reagen A. Tindakan: Larutan dibuang dan dibuat reagen baru, kemudian alat dikalibrasi ulang. Pengujian sampel ditahan menunggu hasil running QC selanjutnya."></textarea>
                            <div class="form-text text-danger" id="charCount">Minimal 10 karakter.</div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('qc-harian.index') }}" class="btn btn-light border px-4">Kembali ke Dashboard</a>
                            <button type="submit" class="btn btn-danger px-4 shadow-sm" id="btnSubmit">
                                <i class="fas fa-unlock me-2"></i>Simpan Laporan & Buka Kunci Parameter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('catatan_investigasi');
        const charCount = document.getElementById('charCount');
        const btnSubmit = document.getElementById('btnSubmit');
        
        textarea.addEventListener('input', function() {
            if(this.value.length < 10) {
                charCount.classList.add('text-danger');
                charCount.classList.remove('text-success');
                charCount.innerHTML = `Minimal 10 karakter. (Saat ini: ${this.value.length})`;
            } else {
                charCount.classList.remove('text-danger');
                charCount.classList.add('text-success');
                charCount.innerHTML = `Panjang karakter sudah mencukupi.`;
            }
        });

        document.getElementById('formCapa').addEventListener('submit', function(e) {
            if(textarea.value.length < 10) {
                e.preventDefault();
                alert('Teks investigasi terlalu singkat!');
                return;
            }
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            btnSubmit.disabled = true;
        });
    });
</script>
@endsection
