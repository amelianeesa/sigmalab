@extends('layouts.app')

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
                    
                    @if(session('error'))
                        <div class="alert alert-danger mb-4"><i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success mb-4"><i class="fas fa-check-circle me-1"></i> {{ session('success') }}</div>
                    @endif
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
                        
                        <!-- LANGKAH 1 -->
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
                        <div class="mb-4 mt-5">
                            <h5 class="fw-bold text-dark border-bottom pb-2"><span class="badge bg-secondary me-2">Langkah 2</span>Tabel Penimbangan Bulk (Air-Drying)</h5>
                            <p class="small text-muted mb-3">Catat jam dan berat nampan (bulk) batubara secara berkala. Bobot konstan tercapai jika laju kehilangan bobot <strong>&le; 0.1% per jam</strong>.</p>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle" id="eqTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="10%" class="text-center">Ke-</th>
                                            <th width="20%">Jam</th>
                                            <th width="25%">Berat Nampan (g)</th>
                                            <th width="35%">Laju Penguapan (%/jam)</th>
                                            <th width="10%">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic rows -->
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="btnAddRow"><i class="fas fa-plus"></i> Tambah Baris</button>
                            
                            <div id="eqStatus" class="alert alert-secondary py-2 text-center fw-bold d-none">
                                Menunggu data...
                            </div>
                            <input type="hidden" name="data_equilibrium" id="data_equilibrium">
                        </div>

                        <!-- LANGKAH 3 -->
                        <div class="mb-4 mt-5" id="sectionPengemasan">
                            <h5 class="fw-bold text-dark border-bottom pb-2"><span class="badge bg-secondary me-2">Langkah 3</span>Pengemasan & Pelabelan Botol</h5>
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

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 btn-lg shadow-sm" id="btnSubmit" disabled>
                                <i class="fas fa-save me-2"></i> Simpan & Lanjut Homogenitas
                            </button>
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
                    <p class="small text-muted mb-2"><strong>Langkah 2:</strong> Dilakukan penimbangan nampan berkala. Bobot konstan tercapai jika laju pengeringan < 0.1% per jam.</p>
                    <p class="small text-muted mb-0"><strong>Langkah 3:</strong> Setelah konstan, batubara dikemas dalam kantong plastik ganda, dimasukkan botol plastik, diberi label, dan dilanjut ke Uji Homogenitas.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.querySelector('#eqTable tbody');
    const btnAdd = document.getElementById('btnAddRow');
    const hiddenData = document.getElementById('data_equilibrium');
    const eqStatus = document.getElementById('eqStatus');
    const btnSubmit = document.getElementById('btnSubmit');
    const sectionPengemasan = document.getElementById('sectionPengemasan');
    let rowCount = 0;

    function addRow() {
        rowCount++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center">${rowCount}</td>
            <td><input type="time" class="form-control form-control-sm in-jam" required></td>
            <td><input type="number" step="0.0001" class="form-control form-control-sm in-berat" placeholder="Contoh: 1550.5" required></td>
            <td><input type="text" class="form-control form-control-sm in-rate bg-light fw-bold" readonly placeholder="-"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-del"><i class="fas fa-times"></i></button></td>
        `;
        tbody.appendChild(tr);
        bindEvents();
    }

    function bindEvents() {
        document.querySelectorAll('.in-berat').forEach(el => el.addEventListener('input', calculateEquilibrium));
        document.querySelectorAll('.in-jam').forEach(el => el.addEventListener('change', calculateEquilibrium));
        document.querySelectorAll('.btn-del').forEach(el => el.onclick = function() {
            this.closest('tr').remove();
            calculateEquilibrium();
        });
    }

    function calculateEquilibrium() {
        const rows = tbody.querySelectorAll('tr');
        let data = [];
        let isKonstan = false;
        let lastRate = null;

        rows.forEach((row, index) => {
            const jam = row.querySelector('.in-jam').value;
            const beratVal = row.querySelector('.in-berat').value;
            const rateInput = row.querySelector('.in-rate');
            
            let currentData = { jam: jam, berat: beratVal, rate: null };
            
            if(beratVal !== '' && jam !== '') {
                const berat = parseFloat(beratVal);
                
                if (index > 0) {
                    const prevRow = data[index - 1];
                    if (prevRow.jam !== '' && prevRow.berat !== '') {
                        const prevBerat = parseFloat(prevRow.berat);
                        
                        // Parse times to calculate diff in hours
                        let t1 = parseTime(prevRow.jam);
                        let t2 = parseTime(jam);
                        
                        if (t2 <= t1) {
                            t2 += 24 * 60; // add 24 hours in minutes if crosses midnight
                        }
                        
                        const diffHours = (t2 - t1) / 60.0;
                        
                        if (diffHours > 0 && prevBerat > 0) {
                            const rate = (Math.abs(berat - prevBerat) / prevBerat) * 100.0 / diffHours;
                            currentData.rate = rate;
                            lastRate = rate;
                            
                            rateInput.value = rate.toFixed(4) + ' %/jam';
                            
                            if (rate <= 0.1) {
                                rateInput.classList.remove('text-danger');
                                rateInput.classList.add('text-success');
                            } else {
                                rateInput.classList.remove('text-success');
                                rateInput.classList.add('text-danger');
                            }
                        } else {
                            rateInput.value = 'Invalid Time/Weight';
                        }
                    }
                } else {
                    rateInput.value = '- (Awal)';
                }
            } else {
                rateInput.value = '';
            }
            data.push(currentData);
        });

        hiddenData.value = JSON.stringify(data);

        if (rows.length >= 2 && lastRate !== null) {
            if (lastRate <= 0.1) {
                isKonstan = true;
                eqStatus.className = 'alert alert-success py-2 text-center fw-bold';
                eqStatus.innerHTML = '<i class="fas fa-check-circle me-1"></i> Bobot Konstan Tercapai (< 0.1% / jam). Silakan isi Langkah 3.';
                btnSubmit.disabled = false;
                sectionPengemasan.style.opacity = '1';
                sectionPengemasan.style.pointerEvents = 'auto';
            } else {
                eqStatus.className = 'alert alert-warning py-2 text-center fw-bold';
                eqStatus.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Belum Konstan. Laju penguapan masih > 0.1% / jam.';
                btnSubmit.disabled = true;
                sectionPengemasan.style.opacity = '0.4';
                sectionPengemasan.style.pointerEvents = 'none';
            }
        } else {
            eqStatus.className = 'alert alert-secondary py-2 text-center fw-bold';
            eqStatus.innerHTML = 'Menunggu minimal 2 data penimbangan...';
            btnSubmit.disabled = true;
            sectionPengemasan.style.opacity = '0.4';
            sectionPengemasan.style.pointerEvents = 'none';
        }
    }

    function parseTime(timeStr) {
        // timeStr is HH:mm
        const parts = timeStr.split(':');
        if (parts.length === 2) {
            return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
        }
        return 0;
    }

    btnAdd.addEventListener('click', addRow);
    
    // Start with 2 empty rows
    addRow(); addRow();
    
    // Initially fade out section 3
    sectionPengemasan.style.opacity = '0.4';
    sectionPengemasan.style.pointerEvents = 'none';
});
</script>
@endsection
