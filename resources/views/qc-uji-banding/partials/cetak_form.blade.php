<div class="p-4 bg-light">
    <div class="row mb-4 no-print">
        <div class="col-md-6">
            <label class="form-label fw-bold">Pilih Modul Cetak</label>
            <select id="cetak-modul-selector" class="form-select border-primary shadow-sm">
                <option value="">-- Pilih Modul --</option>
                <option value="proximate">Proximate Analysis</option>
                <option value="im">Determination of Moisture in Analysis Sample (IM)</option>
                <option value="vm">Determination of Volatile Matter</option>
                <option value="sulfur">Determination of Sulfur by IR Spectrometry</option>
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
        @include('qc-uji-banding.print.modules.im')
        @include('qc-uji-banding.print.modules.vm')
        @include('qc-uji-banding.print.modules.sulfur')
        
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
                } else if (selected === 'im') {
                    syncImData();
                } else if (selected === 'vm') {
                    syncVmData();
                } else if (selected === 'sulfur') {
                    syncSulfurData();
                }
            }
        });
    });

    function syncSulfurData() {
        const sulfurTable = document.querySelector('table.param-table[data-code="TOTAL SULFUR (%AD/DB)"]');
        if(!sulfurTable) {
            console.warn("Tabel Sulfur tidak ditemukan di Tab 1.");
            return;
        }
        
        const pid = sulfurTable.getAttribute('data-pid');
        
        // Header Meta
        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-sulfur-ref-no').innerText = getVal(`input[name="params[${pid}][ref_no]"]`);
        document.getElementById('print-sulfur-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-sulfur-furnace-id').innerText = getVal(`input[name="params[${pid}][furnace_id]"]`);
        document.getElementById('print-sulfur-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);
        
        const sampleId = getVal('input[name="kode_sampel"]');
        
        // Moisture Value
        let imValue = 0;
        const imTable = document.querySelector('table.param-table[data-code="IM"]');
        if (imTable) {
            const imAvgEl = imTable.querySelector('.out-avg-adb');
            if (imAvgEl && !isNaN(parseFloat(imAvgEl.innerText))) {
                imValue = parseFloat(imAvgEl.innerText);
            }
        }

        
        const simplos = sulfurTable.querySelectorAll('.simplo-row');
        const duplos = sulfurTable.querySelectorAll('.duplo-row');
        
        let rowDate = `<tr><td class="text-uppercase text-start ps-2" colspan="2" style="width: 25%;">Date</td>`;
        let rowSample = `<tr><td class="text-uppercase text-start ps-2" colspan="2">SAMPLE ID</td>`;
        let rowMass = `<tr><td class="text-start ps-2" colspan="2">Mass of Sample</td>`;
        let rowTsAdb = `<tr><td class="text-start ps-2" colspan="2">Total sulfur % adb</td>`;
        let rowAvgAdb = `<tr><td class="text-uppercase text-start ps-2" colspan="2">AVERAGE %adb</td>`;
        let rowLimit = `<tr><td class="text-start ps-2" colspan="2">Repeatablity Limit<br>ISO (adb) / ASTM (db)*</td>`;
        let rowMoisture = `<tr><td class="text-start ps-2" colspan="2">Moisture in the<br>analysis sampe %,adb</td>`;
        let rowFinalDb = `<tr><td class="text-start ps-2" colspan="2">Final result %,db</td>`;
        let rowAvgDb = `<tr><td class="text-start ps-2" colspan="2">Average result %,db</td>`;
        let rowDiff = `<tr><td class="text-start ps-2" colspan="2">Absolute Difference<br>ISO (adb) / ASTM (db)*</td>`;

        for (let i = 0; i < simplos.length; i++) {
            const getTVal = (row, selector) => {
                let el = row.querySelector(selector);
                if(el && el.tagName === 'SELECT') return el.options[el.selectedIndex].text;
                return el ? el.value || el.innerText : '';
            };

            let dateVal = getTVal(simplos[i], 'input[type="date"]');
            
            let massS = getTVal(simplos[i], '.in-mass-1');
            let massD = getTVal(duplos[i], '.in-mass-2');
            
            let tsS = parseFloat(getTVal(simplos[i], '.in-hasil-1')) || 0;
            let tsD = parseFloat(getTVal(duplos[i], '.in-hasil-2')) || 0;
            
            let avgAdb = getTVal(simplos[i], '.out-avg-adb');

            // Calculation
            let factor = 100 / (100 - imValue);
            let finalS = (factor * tsS).toFixed(2);
            let finalD = (factor * tsD).toFixed(2);
            let avgDb = ((parseFloat(finalS) + parseFloat(finalD)) / 2).toFixed(2);
            let limitR = (0.02 + (0.03 * parseFloat(avgDb))).toFixed(2);
            let absDiff = Math.abs(parseFloat(finalS) - parseFloat(finalD)).toFixed(2);
            let yesnoVal = getTVal(simplos[i], 'select[name*="[yesno]"]');

            rowDate += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${dateVal}</div></td>`;
            rowSample += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${sampleId}</div></td>`;
            
            rowMass += `<td><div contenteditable="true" class="print-editable w-100 text-center">${massS}</div></td>`;
            rowMass += `<td><div contenteditable="true" class="print-editable w-100 text-center">${massD}</div></td>`;
            
            rowTsAdb += `<td><div contenteditable="true" class="print-editable w-100 text-center">${tsS.toFixed(2)}</div></td>`;
            rowTsAdb += `<td><div contenteditable="true" class="print-editable w-100 text-center">${tsD.toFixed(2)}</div></td>`;
            
            rowAvgAdb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${avgAdb !== '-' ? avgAdb : ''}</div></td>`;
            rowLimit += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${limitR}</div></td>`;
            rowMoisture += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${imValue.toFixed(2)}</div></td>`;
            
            rowFinalDb += `<td><div contenteditable="true" class="print-editable w-100 text-center">${finalS}</div></td>`;
            rowFinalDb += `<td><div contenteditable="true" class="print-editable w-100 text-center">${finalD}</div></td>`;
            
            rowAvgDb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${avgDb}</div></td>`;
            
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100 text-center">${absDiff}</div></td>`;
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100 text-center">${yesnoVal || 'YES/NO)*'}</div></td>`;
        }

        rowDate += `</tr>`;
        rowSample += `</tr>`;
        rowMass += `</tr>`;
        rowTsAdb += `</tr>`;
        rowAvgAdb += `</tr>`;
        rowLimit += `</tr>`;
        rowMoisture += `</tr>`;
        rowFinalDb += `</tr>`;
        rowAvgDb += `</tr>`;
        rowDiff += `</tr>`;

        document.getElementById('print-sulfur-dynamic-tbody').innerHTML = rowDate + rowSample + rowMass + rowTsAdb + rowAvgAdb + rowLimit + rowMoisture + rowFinalDb + rowAvgDb + rowDiff;
    }

    function syncVmData() {
        const vmTable = document.querySelector('table.param-table[data-code="VM"]');
        if(!vmTable) {
            console.warn("Tabel VM tidak ditemukan di Tab 1.");
            return;
        }
        
        const pid = vmTable.getAttribute('data-pid');
        
        // Header Meta
        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-vm-ref-no').innerText = getVal(`input[name="params[${pid}][ref_no]"]`);
        document.getElementById('print-vm-furnace-id').innerText = getVal(`input[name="params[${pid}][furnace_id]"]`);
        document.getElementById('print-vm-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);
        // Date global form
        document.getElementById('print-vm-meta-date').innerText = getVal('input[name="tanggal_terima"]'); // Atau sesuaikan dengan input date di form
        document.getElementById('print-vm-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-vm-temp').innerText = getVal(`input[name="params[${pid}][indicate_t]"]`);
        
        const sampleId = getVal('input[name="kode_sampel"]');

        // Dynamic Table
        const simplos = vmTable.querySelectorAll('.simplo-row');
        const duplos = vmTable.querySelectorAll('.duplo-row');
        
        let rowDate = `<tr><td class="text-uppercase text-start ps-2" colspan="2" style="width: 25%;">DATE</td>`;
        let rowSample = `<tr><td class="text-uppercase text-start ps-2" colspan="2">SAMPLE ID</td>`;
        let rowM1 = `<tr><td class="text-uppercase text-start ps-2">M1</td><td>Gram</td>`;
        let rowM2 = `<tr><td class="text-uppercase text-start ps-2">M2</td><td>Gram</td>`;
        let rowM2M1 = `<tr><td class="text-uppercase text-start ps-2">M2-M1</td><td>Gram</td>`;
        let rowM3 = `<tr><td class="text-uppercase text-start ps-2">M3</td><td>Gram</td>`;
        let rowM2M3 = `<tr><td class="text-uppercase text-start ps-2">M2-M3</td><td>Gram</td>`;
        let rowLoss = `<tr><td class="text-uppercase text-start ps-2" colspan="2">%LOSS</td>`;
        let rowMadb = `<tr><td class="text-uppercase text-start ps-2" colspan="2">%M adb</td>`;
        let rowVm = `<tr><td class="text-uppercase text-start ps-2" colspan="2">%VM, db</td>`;
        let rowDiff = `<tr><td class="text-uppercase text-start ps-2" colspan="2">Absolute Difference</td>`;
        let rowAvg = `<tr><td class="text-uppercase text-start ps-2" colspan="2">AVERAGE%</td>`;

        for (let i = 0; i < simplos.length; i++) {
            const getTVal = (row, selector) => {
                let el = row.querySelector(selector);
                if(el && el.tagName === 'SELECT') return el.options[el.selectedIndex].text;
                return el ? el.value || el.innerText : '';
            };

            let dateVal = getTVal(simplos[i], 'input[type="date"]');
            let diffVal = getTVal(simplos[i], '.out-diff');
            let yesnoVal = getTVal(simplos[i], 'select[name*="[yesno]"]');
            let avgVal = getTVal(simplos[i], '.out-avg-adb');

            rowDate += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${dateVal}</div></td>`;
            rowSample += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${sampleId}</div></td>`;
            
            rowM1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m1-1')}</div></td>`;
            rowM1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m1-2')}</div></td>`;
            
            rowM2 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m2-1')}</div></td>`;
            rowM2 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m2-2')}</div></td>`;
            
            rowM2M1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m2m1-1')}</div></td>`;
            rowM2M1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m2m1-2')}</div></td>`;
            
            rowM3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m3-1')}</div></td>`;
            rowM3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m3-2')}</div></td>`;
            
            rowM2M3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m2m3-1')}</div></td>`;
            rowM2M3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m2m3-2')}</div></td>`;
            
            rowLoss += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-loss-1')}</div></td>`;
            rowLoss += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-loss-2')}</div></td>`;

            // %M adb is shared, rowspan 2 in UI, .in-im-1
            let mAdb = getTVal(simplos[i], '.in-im-1');
            rowMadb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${mAdb}</div></td>`;
            
            // %VM is in .in-hasil-1 and .in-hasil-2
            let hasilSimplo = simplos[i].querySelector('.in-hasil-1');
            let vmSimplo = hasilSimplo ? (hasilSimplo.value || hasilSimplo.innerText) : '';
            let hasilDuplo = duplos[i] ? duplos[i].querySelector('.in-hasil-2') : null;
            let vmDuplo = hasilDuplo ? (hasilDuplo.value || hasilDuplo.innerText) : '';

            rowVm += `<td><div contenteditable="true" class="print-editable w-100">${vmSimplo}</div></td>`;
            rowVm += `<td><div contenteditable="true" class="print-editable w-100">${vmDuplo}</div></td>`;
            
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100 text-center">${diffVal !== '-' ? diffVal : ''}</div></td>`;
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100 text-center">${yesnoVal || 'YES/NO'}</div></td>`;

            rowAvg += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${avgVal !== '-' ? avgVal : ''}</div></td>`;
        }

        rowDate += `</tr>`;
        rowSample += `</tr>`;
        rowM1 += `</tr>`;
        rowM2 += `</tr>`;
        rowM2M1 += `</tr>`;
        rowM3 += `</tr>`;
        rowM2M3 += `</tr>`;
        rowLoss += `</tr>`;
        rowMadb += `</tr>`;
        rowVm += `</tr>`;
        rowDiff += `</tr>`;
        rowAvg += `</tr>`;

        document.getElementById('print-vm-dynamic-tbody').innerHTML = rowDate + rowSample + rowM1 + rowM2 + rowM2M1 + rowM3 + rowM2M3 + rowLoss + rowMadb + rowVm + rowDiff + rowAvg;
    }

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

    function syncImData() {
        const imTable = document.querySelector('table.param-table[data-code="IM"]');
        if(!imTable) {
            console.warn("Tabel IM tidak ditemukan di Tab 1.");
            return;
        }
        
        const pid = imTable.getAttribute('data-pid');
        
        // Header Meta
        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-im-ref-no').innerText = getVal(`input[name="params[${pid}][ref_no]"]`);
        document.getElementById('print-im-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);
        document.getElementById('print-im-oven-id').innerText = getVal(`input[name="params[${pid}][furnace_id]"]`);
        document.getElementById('print-im-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-im-time').innerText = getVal(`input[name="params[${pid}][time]"]`);
        document.getElementById('print-im-temp').innerText = getVal(`input[name="params[${pid}][indicate_t]"]`);
        
        const sampleId = getVal('input[name="kode_sampel"]');

        // Dynamic Table
        const simplos = imTable.querySelectorAll('.simplo-row');
        const duplos = imTable.querySelectorAll('.duplo-row');
        
        let colsHtml = '';
        
        let rowDate = `<tr><td class="text-uppercase text-start ps-2" colspan="2" style="width: 20%;">DATE OF ANALYSIS</td>`;
        let rowSample = `<tr><td class="text-uppercase text-start ps-2" colspan="2">SAMPLE ID</td>`;
        let rowDish = `<tr><td class="text-uppercase text-start ps-2" colspan="2">DISH NO.</td>`;
        let rowM1 = `<tr><td class="text-uppercase text-start ps-2">M1</td><td>gram</td>`;
        let rowM2 = `<tr><td class="text-uppercase text-start ps-2">M2</td><td>gram</td>`;
        let rowM3 = `<tr><td class="text-uppercase text-start ps-2">M3</td><td>gram</td>`;
        let rowA = `<tr><td class="text-uppercase text-start ps-2">A</td><td>gram</td>`;
        let rowB = `<tr><td class="text-uppercase text-start ps-2">B</td><td>gram</td>`;
        let rowM = `<tr><td class="text-uppercase text-start ps-2">M</td><td>%</td>`;
        let rowDiff = `<tr><td class="text-uppercase text-start ps-2" colspan="2">Absolute Diference</td>`;
        let rowAvg = `<tr><td class="text-uppercase text-start ps-2" colspan="2">AVERAGE % (reported)</td>`;

        for (let i = 0; i < simplos.length; i++) {
            const getTVal = (row, selector) => {
                let el = row.querySelector(selector);
                if(el && el.tagName === 'SELECT') return el.options[el.selectedIndex].text;
                return el ? el.value || el.innerText : '';
            };

            let dateVal = getTVal(simplos[i], 'input[type="date"]');
            let diffVal = getTVal(simplos[i], '.out-diff');
            let yesnoVal = getTVal(simplos[i], 'select[name*="[yesno]"]');
            if(yesnoVal) yesnoVal += ')*';
            let avgVal = getTVal(simplos[i], '.out-avg-adb');

            rowDate += `<td colspan="2"><div contenteditable="true" class="print-editable w-100">${dateVal}</div></td>`;
            rowSample += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${sampleId}</div></td>`;
            
            rowDish += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-dish-1')}</div></td>`;
            rowDish += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-dish-2')}</div></td>`;
            
            rowM1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m1-1')}</div></td>`;
            rowM1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m1-2')}</div></td>`;
            
            rowM2 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m2-1')}</div></td>`;
            rowM2 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m2-2')}</div></td>`;
            
            rowM3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-m3-1')}</div></td>`;
            rowM3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-m3-2')}</div></td>`;
            
            rowA += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-a-1')}</div></td>`;
            rowA += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-a-2')}</div></td>`;
            
            rowB += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-b-1')}</div></td>`;
            rowB += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-b-2')}</div></td>`;
            
            let hasilSimplo = simplos[i].querySelector('.in-hasil-1');
            let mSimplo = hasilSimplo ? (hasilSimplo.value || hasilSimplo.innerText) : '';
            // Wait, for IM, .in-hasil-1 is an input or what? In L100 of proximate_table: <td class="bg-warning..."><input type="text" class="form-control ... in-hasil-1" readonly ...></td>
            // It uses .value.
            let hasilDuplo = duplos[i] ? duplos[i].querySelector('.in-hasil-2') : null;
            let mDuplo = hasilDuplo ? (hasilDuplo.value || hasilDuplo.innerText) : '';

            rowM += `<td><div contenteditable="true" class="print-editable w-100">${mSimplo}</div></td>`;
            rowM += `<td><div contenteditable="true" class="print-editable w-100">${mDuplo}</div></td>`;
            
            // Format for absolute diff in the excel mockup: Left col is diff value, right col is Yes/No)*
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100 text-center">${diffVal !== '-' ? diffVal : ''}</div></td>`;
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100 text-center">${yesnoVal || 'Yes/No)*'}</div></td>`;

            rowAvg += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${avgVal !== '-' ? avgVal : ''}</div></td>`;
        }

        rowDate += `</tr>`;
        rowSample += `</tr>`;
        rowDish += `</tr>`;
        rowM1 += `</tr>`;
        rowM2 += `</tr>`;
        rowM3 += `</tr>`;
        rowA += `</tr>`;
        rowB += `</tr>`;
        rowM += `</tr>`;
        rowDiff += `</tr>`;
        rowAvg += `</tr>`;

        document.getElementById('print-im-dynamic-tbody').innerHTML = rowDate + rowSample + rowDish + rowM1 + rowM2 + rowM3 + rowA + rowB + rowM + rowDiff + rowAvg;
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
