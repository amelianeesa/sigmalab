{{-- Tab Cetak Form: Control Panel & Preview Cetak --}}
<div class="p-4 bg-light">
    <div class="row mb-4 no-print">
        <div class="col-md-6">
            <label class="form-label fw-bold">Pilih Modul Cetak</label>
            <select id="cetak-modul-selector" class="form-select border-primary shadow-sm">
                <option value="">-- Pilih Modul --</option>
                <option value="proximate">Proximate Analysis</option>
                <!-- Modul lain akan ditambahkan di sini -->
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end gap-2">
            <button type="button" class="btn btn-primary shadow-sm" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak Dokumen
            </button>
            <button type="button" class="btn btn-success shadow-sm" id="btn-save-print" onclick="savePrintData()">
                <i class="fas fa-save me-2"></i>Simpan Perubahan
            </button>
        </div>
    </div>

    <!-- A4 Paper Preview Container -->
    <div class="print-preview-a4 bg-white shadow mx-auto p-5" id="print-area">
        <div id="print-placeholder" class="text-center text-muted py-5 mt-5">
            <i class="fas fa-file-alt fa-3x mb-3 text-secondary opacity-50"></i>
            <h5>Pilih modul di atas untuk melihat preview cetak</h5>
        </div>

        <!-- Modul-modul cetak akan di-include di sini (awalnya di-hide semua) -->
        @include('qc-uji-banding.print.modules.proximate')
        
    </div>
</div>

<style>
    /* Styling untuk layar/preview */
    .print-preview-a4 {
        width: 21cm;
        min-height: 29.7cm;
        border: 1px solid #ddd;
    }
    .print-editable {
        outline: none;
        min-height: 1.5em;
        padding: 2px 5px;
        transition: background 0.2s;
    }
    .print-editable:hover, .print-editable:focus {
        background: #fff3cd; /* Highlight kuning tipis saat diedit */
        border-radius: 3px;
    }

    /* Styling khusus saat Print (Ctrl+P) */
    @media print {
        @page { size: A4 portrait; margin: 1cm; }
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
        }
        .no-print { display: none !important; }
        .print-editable { background: transparent !important; padding: 0; }
        
        /* Memastikan warna background gray di tabel tetap tercetak */
        table td.bg-secondary {
            background-color: #808080 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modulSelector = document.getElementById('cetak-modul-selector');
        
        modulSelector.addEventListener('change', function() {
            // Sembunyikan semua modul & placeholder
            document.getElementById('print-placeholder').style.display = 'none';
            document.querySelectorAll('.print-module-container').forEach(el => el.style.display = 'none');
            
            const selected = this.value;
            if (!selected) {
                document.getElementById('print-placeholder').style.display = 'block';
                return;
            }

            // Tampilkan modul yang dipilih
            const modContainer = document.getElementById('print-module-' + selected);
            if(modContainer) {
                modContainer.style.display = 'block';
                
                // Jalankan sinkronisasi data khusus untuk modul yang dipilih
                if(selected === 'proximate') {
                    syncProximateData();
                }
            }
        });
    });

    function syncProximateData() {
        // Ambil Data Header
        const refNoInput = document.querySelector('input[name*="[ref_no]"]');
        const stdMethodInput = document.querySelector('input[name*="[std_method]"]');
        const sampleIdInput = document.querySelector('input[name="kode_sampel"]');

        if(refNoInput) document.getElementById('print-prox-ref-no').innerText = refNoInput.value;
        if(stdMethodInput) document.getElementById('print-prox-std-method').innerText = stdMethodInput.value;
        if(sampleIdInput) document.getElementById('print-prox-sample-id').innerText = sampleIdInput.value;

        // Ambil Data dari Tabel Proximate (Tab 2)
        // IM
        const imAdb = document.querySelector('tr[data-param="IM"] .prox-adb');
        if(imAdb) document.getElementById('print-prox-im-adb').innerText = imAdb.innerText !== '—' ? imAdb.innerText : '';
        
        // ASH
        const ashAdb = document.querySelector('tr[data-param="ASH"] .prox-adb');
        const ashDb = document.querySelector('tr[data-param="ASH"] .prox-db');
        if(ashAdb) document.getElementById('print-prox-ash-adb').innerText = ashAdb.innerText !== '—' ? ashAdb.innerText : '';
        if(ashDb) document.getElementById('print-prox-ash-db').innerText = ashDb.innerText !== '—' ? ashDb.innerText : '';

        // VM
        const vmAdb = document.querySelector('tr[data-param="VM"] .prox-adb');
        const vmDb = document.querySelector('tr[data-param="VM"] .prox-db');
        if(vmAdb) document.getElementById('print-prox-vm-adb').innerText = vmAdb.innerText !== '—' ? vmAdb.innerText : '';
        if(vmDb) document.getElementById('print-prox-vm-db').innerText = vmDb.innerText !== '—' ? vmDb.innerText : '';

        // FC
        const fcAdb = document.querySelector('tr[data-param="FC"] .prox-adb');
        const fcDb = document.querySelector('tr[data-param="FC"] .prox-db');
        if(fcAdb) document.getElementById('print-prox-fc-adb').innerText = fcAdb.innerText !== '—' ? fcAdb.innerText : '';
        if(fcDb) document.getElementById('print-prox-fc-db').innerText = fcDb.innerText !== '—' ? fcDb.innerText : '';
    }

    function savePrintData() {
        const selected = document.getElementById('cetak-modul-selector').value;
        if(!selected) {
            alert('Pilih modul cetak terlebih dahulu!');
            return;
        }

        // Contoh cara mengambil teks hasil editan dari contenteditable
        if (selected === 'proximate') {
            const dataToSave = {
                modul: 'proximate',
                ref_no: document.getElementById('print-prox-ref-no').innerText,
                sample_id: document.getElementById('print-prox-sample-id').innerText,
                im_adb: document.getElementById('print-prox-im-adb').innerText,
                ash_adb: document.getElementById('print-prox-ash-adb').innerText,
                ash_db: document.getElementById('print-prox-ash-db').innerText,
                // ... dsb
            };
            
            console.log("Data siap dikirim ke backend:", dataToSave);
            alert("Hasil editan cetak berhasil ditangkap! Cek Console (F12) untuk melihat strukturnya.");
            
            // Disini nantinya bisa ditaruh AJAX (fetch/axios) untuk mengirim data ke database
            /*
            fetch('/api/save-print', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '...' },
                body: JSON.stringify(dataToSave)
            });
            */
        }
    }
</script>
