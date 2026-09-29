@extends('layouts.app')
@section('title', 'Preparasi - QC In-House')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Tahap 2: Preparasi & Ekuilibrium</li>
    </x-qc-breadcrumb>

    <div class="row mt-3">
        <div class="col-xl-9 col-lg-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-dark mb-1"><i class="fas fa-balance-scale text-primary me-2"></i>Tahap 2: Preparasi (Air-Drying & Pengemasan)</h3>
                    <p class="text-muted mb-4">Lakukan penghamparan batubara bulk, periksa laju kehilangan bobot hingga mencapai ekuilibrium (< 0.1% per jam), lalu kemas ke dalam botol.</p>
                    
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('qc-inhouse.preparasi.store', $batch->sampel_inhouse_id) }}" method="POST" id="formPreparasi">
                        @csrf
                        
                        <!-- langkah 1: identitas -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark border-bottom pb-2"><span class="badge bg-secondary me-2">Langkah 1</span>Identitas Acuan & Hamparan</h5>
                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Sampel</label>
                                    <input type="text" class="form-control bg-light" value="{{ $batch->nama_sampel }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Metode Acuan <span class="text-danger">*</span></label>
                                    <select name="metode_acuan" class="form-select" required>
                                        <option value="">-- Pilih Metode Acuan --</option>
                                        <option value="astm" {{ old('metode_acuan', $batch->metode_acuan) == 'astm' ? 'selected' : '' }}>ASTM</option>
                                        <option value="iso" {{ old('metode_acuan', $batch->metode_acuan) == 'iso' ? 'selected' : '' }}>ISO</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- LANGKAH 2 -->
                        <div class="mb-4 mt-5" id="sectionPengemasan">
                            <h5 class="fw-bold text-dark border-bottom pb-2"><span class="badge bg-secondary me-2">Langkah 2</span>Pengemasan & Pelabelan Botol</h5>
                            <p class="small text-muted mb-3">Bagian ini hanya boleh diisi setelah batubara mencapai bobot konstan, dikemas dalam plastik ganda, dan dimasukkan ke dalam botol.</p>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Kode Batch <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_batch" class="form-control" value="{{ old('kode_batch', $batch->kode_batch ?? 'INH-'.date('ym')) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jumlah Botol Total <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_botol" class="form-control" value="{{ old('jumlah_botol', $batch->jumlah_botol ?? 50) }}" min="10" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Nomor Awal Botol <span class="text-danger">*</span></label>
                                    <input type="number" name="nomor_awal_botol" class="form-control" value="{{ old('nomor_awal_botol', $batch->nomor_awal_botol ?? 1) }}" min="1" required>
                                </div>
                            </div>

                            <div class="mt-4 mb-2">
                                <label class="form-label fw-bold">Catatan Preparasi (Opsional)</label>
                                <textarea name="catatan_preparasi" class="form-control" rows="2" placeholder="Suhu ruangan, kondisi ayak 60 mesh, dll...">{{ old('catatan_preparasi', $batch->catatan_preparasi) }}</textarea>
                            </div>
                        </div>

                            <div class="d-grid d-md-flex justify-content-md-end mt-4">
                                @if($batch->status === 'preparasi')
                                <button type="submit" class="btn btn-primary rounded-pill shadow-sm px-4 py-2" id="btnSubmit">
                                    <i class="fas fa-save me-2"></i> Simpan & Lanjut Homogenitas
                                </button>
                                @endif
                            </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Info -->
        <div class="col-xl-3 col-lg-2">
            <div class="card shadow-sm border-0 bg-info bg-opacity-10 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold text-info"><i class="fas fa-info-circle me-1"></i> SOP Air-Drying</h6>
                    <p class="small text-muted mb-2"><strong>Langkah 1:</strong> Sampel batubara giling dihamparkan di nampan untuk memastikan tidak ada pengotor.</p>
                    <p class="small text-muted mb-0"><strong>Langkah 2:</strong> Batubara dikemas dalam kantong plastik ganda, dimasukkan botol plastik, diberi label, dan dilanjut ke Uji Homogenitas.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
