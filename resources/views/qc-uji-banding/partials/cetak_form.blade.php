<div class="p-2 p-md-4 bg-light">
    <div class="row mb-3 no-print g-2">
        <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Pilih Modul Cetak</label>
            <select id="cetak-modul-selector" class="form-select border-primary shadow-sm">
                <option value="">-- Pilih Modul --</option>
                <option value="proximate">Proximate Analysis</option>
                <option value="im">Determination of Moisture in Analysis Sample (IM)</option>
                <option value="ash">Determination of Ash Content</option>
                <option value="vm">Determination of Volatile Matter</option>
                <option value="sulfur">Determination of Sulfur by IR Spectrometry</option>
                <option value="gcv">Determination of Gross Calorific Value</option>
                <!-- Modul lain akan ditambahkan di sini -->
            </select>
        </div>
        <div class="col-12 col-md-6 d-flex align-items-end gap-2">
            <button type="button" class="btn btn-primary shadow-sm" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak Dokumen
            </button>
            <button type="button" class="btn btn-success shadow-sm" id="btn-save-print" onclick="savePrintData()">
                <i class="fas fa-save me-2"></i>Simpan Perubahan
            </button>
        </div>
    </div>

    <div class="print-preview-viewport">
        <div id="print-scaler">
            <div class="print-preview-a4 bg-white shadow mx-auto p-5" id="print-area">
                <div id="print-placeholder" class="text-center text-muted py-5 mt-5">
                    <i class="fas fa-file-alt fa-3x mb-3 text-secondary opacity-50"></i>
                    <h5>Pilih modul di atas untuk melihat preview cetak</h5>
                </div>

                @include('qc-uji-banding.print.modules.proximate')
                @include('qc-uji-banding.print.modules.im')
                @include('qc-uji-banding.print.modules.ash')
                @include('qc-uji-banding.print.modules.vm')
                @include('qc-uji-banding.print.modules.sulfur')
                @include('qc-uji-banding.print.modules.gcv')
            </div>
        </div>
    </div>
</div>

<style>
    
    .print-preview-a4 {
        width: 21cm;
        min-height: 29.7cm;
        border: 1px solid #ddd;
        background-color: #fff;
    }
    .print-editable {
        outline: none;
        min-height: 1.5em;
        padding: 2px 5px;
        transition: background 0.2s;
    }
    .print-editable:hover, .print-editable:focus {
        background: #fff3cd;
        border-radius: 3px;
    }

    /* Salinan/wrapper cetak tidak tampil di layar */
    #print-clone { display: none; }

    .print-preview-viewport {
        width: 100%;
        overflow: hidden;
        position: relative;
    }
    #print-scaler {
        width: 21cm;
        transform-origin: top left;
        position: absolute;   /* tinggi viewport diatur JS (fitPreview) */
        top: 0;
        left: 0;
    }
    #print-scaler .print-preview-a4 {
        margin: 0 !important; /* hilangkan efek mx-auto */
    }

    @media (max-width: 767.98px) {
        .print-preview-a4 {
            padding: 1rem !important;
        }
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            height: auto !important;
            overflow: visible !important;
        }

        body.printing-mode > *:not(#print-clone) {
            display: none !important;
        }

        #print-clone {
            display: block !important;
            width: 100%;
            padding: 1.5cm;
            box-sizing: border-box;
            background: #fff;
        }

        /* Matikan skala & posisi absolut milik preview */
        #print-scaler {
            position: static !important;
            transform: none !important;
            width: 100% !important;
        }
        .print-preview-viewport {
            height: auto !important;
            overflow: visible !important;
        }

        #print-clone .print-preview-a4 {
            width: 100% !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: #fff !important;
        }

        #print-clone #print-placeholder { display: none !important; }

        #print-clone .print-editable {
            background: transparent !important;
            padding: 0 !important;
            border: none !important;
            outline: none !important;
        }

        #print-clone table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        #print-clone tr { page-break-inside: avoid; }

        #print-clone .table-bordered,
        #print-clone .table-bordered th,
        #print-clone .table-bordered td {
            border: 1px solid #000 !important;
        }

        #print-clone .bg-secondary {
            background-color: #808080 !important;
        }

        #print-clone img,
        #print-clone svg {
            display: inline-block !important;
            visibility: visible !important;
            max-width: 100%;
        }

        #print-clone * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .hide-on-print {
            display: none !important;
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
                
                if(selected === 'proximate') {
                    syncProximateData();
                } else if (selected === 'im') {
                    syncImData();
                } else if (selected === 'vm') {
                    syncVmData();
                } else if (selected === 'sulfur') {
                    syncSulfurData();
                }else if (selected === 'gcv'){
                    syncGcvData();
                } else if (selected === 'ash') {
                    syncAshData();
                }
            }
            setTimeout(fitPreview, 50);
        });
    });

    let printMarker = null;

    window.addEventListener('beforeprint', () => {
        const area = document.getElementById('print-area');
        if (!area) return;

        // Penanda posisi asli supaya bisa dikembalikan
        printMarker = document.createElement('div');
        area.parentNode.insertBefore(printMarker, area);

        const wrapper = document.createElement('div');
        wrapper.id = 'print-clone';
        wrapper.appendChild(area);          // dipindah, bukan disalin
        document.body.appendChild(wrapper);
        document.body.classList.add('printing-mode');
    });

    window.addEventListener('afterprint', () => {
        const wrapper = document.getElementById('print-clone');
        const area = document.getElementById('print-area');

        if (printMarker && area) {
            printMarker.parentNode.insertBefore(area, printMarker);
            printMarker.remove();
            printMarker = null;
        }
        if (wrapper) wrapper.remove();
        document.body.classList.remove('printing-mode');

        setTimeout(fitPreview, 50);
    });

    function fitPreview() {
        const vp = document.querySelector('.print-preview-viewport');
        const scaler = document.getElementById('print-scaler');
        if (!vp || !scaler) return;

        const paperW = 793.7; // 21cm dalam px
        const scale = Math.min(1, vp.clientWidth / paperW);

        scaler.style.transform = `scale(${scale})`;
        // scrollHeight lebih akurat untuk konten yang berubah-ubah
        vp.style.height = (scaler.scrollHeight * scale) + 'px';
    }

    window.addEventListener('load', fitPreview);
    window.addEventListener('resize', fitPreview);

    // Tinggi kertas berubah saat modul diganti / data disinkronkan
    document.addEventListener('DOMContentLoaded', () => {
        const scaler = document.getElementById('print-scaler');
        if (scaler && window.ResizeObserver) {
            new ResizeObserver(fitPreview).observe(scaler);
        }
    });

    

    function syncSulfurData() {
        const sulfurTable = document.querySelector('table.param-table[data-code="TS"]'); 
        if (!sulfurTable) {
            console.warn("Tabel Sulfur/TS tidak ditemukan di Tab 1.");
            return;
        }
        
        const pid = sulfurTable.getAttribute('data-pid');
        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-sulfur-ref-no').innerText = getVal(`input[name="params[${pid}][ref_no]"]`);
        document.getElementById('print-sulfur-furnace-id').innerText = getVal(`input[name="params[${pid}][furnace_id]"]`);
        document.getElementById('print-sulfur-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-sulfur-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);
        
        let stdMethodText = getVal(`input[name="params[${pid}][std_method]"]`).toUpperCase();
        const sampleId = getVal('input[name="kode_sampel"]');

        const simplos = sulfurTable.querySelectorAll('.simplo-row');
        const duplos = sulfurTable.querySelectorAll('.duplo-row');
        
        const imTable = document.querySelector('table.param-table[data-code="IM"]');
        const imTbodies = imTable ? imTable.querySelectorAll('tbody.proximate-tbody') : [];

        let rowDate = `<tr><td class="text-start ps-2" colspan="2" style="width: 40%;">Date</td>`;
        let rowSample = `<tr><td class="text-uppercase text-start ps-2" colspan="2">SAMPLE ID</td>`;
        let rowMass = `<tr><td class="text-start ps-2" colspan="2">Mass of Sample</td>`;
        let rowTsAdb = `<tr><td class="text-start ps-2" colspan="2">Total sulfur % adb</td>`;
        let rowAvgAdb = `<tr><td class="text-uppercase text-start ps-2" colspan="2">AVERAGE %adb</td>`;
        let rowRepLimit = `<tr><td class="text-start ps-2" colspan="2">Repeatability Limit<br>ISO (adb) / ASTM (db)*</td>`;
        let rowMoisture = `<tr><td class="text-start ps-2" colspan="2">Moisture in the<br>analysis sampe %,adb</td>`;
        let rowFinalDb = `<tr><td class="text-start ps-2" colspan="2">Final result %,db</td>`;
        let rowAvgDb = `<tr><td class="text-start ps-2" colspan="2">Average result %,db</td>`;
        let rowDiff = `<tr><td class="text-start ps-2" colspan="2">Absolute Difference<br>ISO (adb) / ASTM</td>`;

        const fmt = val => (!isNaN(val) && val !== '') ? parseFloat(val).toFixed(2) : '';

        for (let i = 0; i < simplos.length; i++) {
            const getTVal = (row, selector) => {
                let el = row ? row.querySelector(selector) : null;
                return el ? el.value || el.innerText : '';
            };

            let dateVal = getTVal(simplos[i], 'input[type="date"]');
            let mass1 = getTVal(simplos[i], '.in-mass-1') || getTVal(simplos[i], '.in-m1-1'); 
            let mass2 = getTVal(duplos[i], '.in-mass-2') || getTVal(duplos[i], '.in-m1-2');
            let ts_adb_1 = parseFloat(getTVal(simplos[i], '.in-hasil-1')) || 0;
            let ts_adb_2 = parseFloat(getTVal(duplos[i], '.in-hasil-2')) || 0;
            
            let avg_adb = (ts_adb_1 + ts_adb_2) / 2;

            let m_adb = 0;
            if (imTbodies[i]) {
                let m_el = imTbodies[i].querySelector('.out-avg-adb');
                m_adb = m_el ? parseFloat(m_el.innerText) || 0 : 0;
            }

            let final_db_1 = m_adb < 100 ? (100 / (100 - m_adb)) * ts_adb_1 : 0;
            let final_db_2 = m_adb < 100 ? (100 / (100 - m_adb)) * ts_adb_2 : 0;
            let avg_db = (final_db_1 + final_db_2) / 2;
            let diff_db = Math.abs(final_db_1 - final_db_2);

            let selectRumus = `
                <select class="form-select form-select-sm border-0 text-center hide-on-print mb-1 text-primary" onchange="hitungToleransiDinamic(this, ${avg_db})">
                    <option value="">-- Pilih Rumus Toleransi --</option>
                    <option value="0.02 + 0.03 u">0.02 + 0.03 u (Method A)</option>
                    <option value="0.04 + 0.05 u">0.04 + 0.05 u (Method B)</option>
                </select>
            `;

            rowDate += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${dateVal}</div></td>`;
            rowSample += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold text-center">${i===0 ? sampleId : ''}</div></td>`;
            rowMass += `<td><div contenteditable="true" class="print-editable w-100">${mass1}</div></td><td><div contenteditable="true" class="print-editable w-100">${mass2}</div></td>`;
            rowTsAdb += `<td><div contenteditable="true" class="print-editable w-100">${fmt(ts_adb_1)}</div></td><td><div contenteditable="true" class="print-editable w-100">${fmt(ts_adb_2)}</div></td>`;
            
            rowAvgAdb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${fmt(avg_adb)}</div></td>`;
            
    
            rowRepLimit += `<td colspan="2" class="align-middle p-1">
                ${selectRumus}
                <div contenteditable="true" class="print-editable w-100 text-center fw-bold result-toleransi"></div>
            </td>`;
            
            rowMoisture += `<td colspan="2"><div contenteditable="true" class="print-editable w-100">${fmt(m_adb)}</div></td>`;
            
            rowFinalDb += `<td><div contenteditable="true" class="print-editable w-100">${fmt(final_db_1)}</div></td><td><div contenteditable="true" class="print-editable w-100">${fmt(final_db_2)}</div></td>`;
            rowAvgDb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${fmt(avg_db)}</div></td>`;
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100">${fmt(diff_db)}</div></td><td><div contenteditable="true" class="print-editable w-100 text-danger">YES/NO)*</div></td>`;
        }

        let finalHtml = rowDate + `</tr>` + rowSample + `</tr>` + rowMass + `</tr>` + rowTsAdb + `</tr>` + rowAvgAdb + `</tr>` + rowRepLimit + `</tr>` + rowMoisture + `</tr>` + rowFinalDb + `</tr>` + rowAvgDb + `</tr>` + rowDiff + `</tr>`;
        
        document.getElementById('print-sulfur-dynamic-tbody').innerHTML = finalHtml;
    }

    window.hitungToleransiDinamic = function(selectElement, u_value) {
        let targetDiv = selectElement.parentElement.querySelector('.result-toleransi');
        let rumusStr = selectElement.value; // Contoh nilai: "0.02 + 0.03 u"
        
        if (!rumusStr || u_value === 0) {
            targetDiv.innerText = '';
            return;
        }
        
        try {
            let safeFormula = rumusStr.replace(/(\d)\s*(u)/gi, '$1 * $2');
            
            let finalMath = safeFormula.replace(/u/gi, u_value);
            let hasil = new Function('return ' + finalMath)();
        
            targetDiv.innerText = parseFloat(hasil).toFixed(2);
        } catch (error) {
            console.error("Gagal menghitung rumus:", error);
            targetDiv.innerText = 'Error';
        }
    };

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
        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-prox-ref-no').innerText = getVal('input[name*="[ref_no]"]');
        document.getElementById('print-prox-std-method').innerText = getVal('input[name*="[std_method]"]');
        
        let sampleIdVal = getVal('input[name="kode_sampel"]');

        const imTable = document.querySelector('table[data-code="IM"]');
        const ashTable = document.querySelector('table[data-code="ASH"]');
        const vmTable = document.querySelector('table[data-code="VM"]');

        let tbodies = [];
        if (imTable) tbodies = imTable.querySelectorAll('tbody.proximate-tbody');
        else if (ashTable) tbodies = ashTable.querySelectorAll('tbody.proximate-tbody');
        else if (vmTable) tbodies = vmTable.querySelectorAll('tbody.proximate-tbody');
        
        let numTests = tbodies.length > 0 ? tbodies.length : 1;

        let colGroupHtml = `<col style="width: 40%;">`; 
        let colWidth = 60 / (numTests * 2); 
        for(let i=0; i < numTests * 2; i++) {
            colGroupHtml += `<col style="width: ${colWidth}%;">`;
        }
        document.getElementById('print-prox-colgroup').innerHTML = colGroupHtml;

        let headerRow0 = `<td class="text-uppercase text-start ps-2 fw-bold">SAMPLE ID</td>`;
        for(let i=0; i < numTests; i++) {
            let text = i === 0 ? sampleIdVal : ''; 
            headerRow0 += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold text-center">${text}</div></td>`;
        }

        let headerRow1 = `<td></td>`;
        for(let i=0; i < numTests; i++) {
            headerRow1 += `<th colspan="2" class="align-middle text-center">Moisture Basis</th>`;
        }

        let headerRow2 = `<th class="align-middle text-uppercase text-center">PARAMETER</th>`;
        for(let i=0; i < numTests; i++) {
            headerRow2 += `<th class="text-center">%adb</th><th class="text-center">%db</th>`;
        }
        
        document.getElementById('print-prox-header-0').innerHTML = headerRow0;
        document.getElementById('print-prox-header-1').innerHTML = headerRow1;
        document.getElementById('print-prox-header-2').innerHTML = headerRow2;

        let imRow = `<td class="text-start ps-2">Moisture in analysis sample (M)</td>`;
        let ashRow = `<td class="text-start ps-2">Ash content (A)</td>`;
        let vmRow = `<td class="text-start ps-2">Volatile matter (VM)</td>`;
        let fcRow = `<td class="text-start ps-2">Fixed Carbon (FC)</td>`;

        const getAdb = (table, idx) => {
            if (!table) return 0;
            const tbody = table.querySelectorAll('tbody.proximate-tbody')[idx];
            if (!tbody) return 0;
            let val = parseFloat(tbody.querySelector('.out-avg-adb')?.innerText);
            return isNaN(val) ? 0 : val;
        };

        const fmt = (val) => val === 0 ? '' : val.toFixed(2);

        for (let i = 0; i < numTests; i++) {
            let imAdb = getAdb(imTable, i);
            let ashAdb = getAdb(ashTable, i);
            let vmAdb = getAdb(vmTable, i);
            
            let fcAdb = 0;
            if(imAdb > 0 || ashAdb > 0 || vmAdb > 0) {
                fcAdb = 100 - imAdb - ashAdb - vmAdb;
            }

            let ashDb = imAdb < 100 ? (100 / (100 - imAdb)) * ashAdb : 0;
            let vmDb = imAdb < 100 ? (100 / (100 - imAdb)) * vmAdb : 0;
            let fcDb = fcAdb > 0 ? (100 - ashDb - vmDb) : 0;

            imRow += `<td><div contenteditable="true" class="print-editable w-100">${fmt(imAdb)}</div></td><td class="bg-secondary"></td>`;
            ashRow += `<td><div contenteditable="true" class="print-editable w-100">${fmt(ashAdb)}</div></td><td><div contenteditable="true" class="print-editable w-100">${fmt(ashDb)}</div></td>`;
            vmRow += `<td><div contenteditable="true" class="print-editable w-100">${fmt(vmAdb)}</div></td><td><div contenteditable="true" class="print-editable w-100">${fmt(vmDb)}</div></td>`;
            fcRow += `<td><div contenteditable="true" class="print-editable w-100">${fmt(fcAdb)}</div></td><td><div contenteditable="true" class="print-editable w-100">${fmt(fcDb)}</div></td>`;
        }

        document.getElementById('print-prox-row-im').innerHTML = imRow;
        document.getElementById('print-prox-row-ash').innerHTML = ashRow;
        document.getElementById('print-prox-row-vm').innerHTML = vmRow;
        document.getElementById('print-prox-row-fc').innerHTML = fcRow;
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

    function syncAshData() {
        const ashTable = document.querySelector('table.param-table[data-code="ASH"]');
        if (!ashTable) {
            console.warn("Tabel ASH tidak ditemukan di Tab 1.");
            return;
        }
        
        const pid = ashTable.getAttribute('data-pid');
        
        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-ash-ref-no').innerText = getVal(`input[name="params[${pid}][ref_no]"]`);
        document.getElementById('print-ash-furnace-id').innerText = getVal(`input[name="params[${pid}][furnace_id]"]`);
        document.getElementById('print-ash-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-ash-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);
        document.getElementById('print-ash-indicate-t').innerText = getVal(`input[name="params[${pid}][indicate_t]"]`);
        
        const sampleId = getVal('input[name="kode_sampel"]');

        const simplos = ashTable.querySelectorAll('.simplo-row');
        const duplos = ashTable.querySelectorAll('.duplo-row');
        
        const imTable = document.querySelector('table.param-table[data-code="IM"]');
        const imSimplos = imTable ? imTable.querySelectorAll('.simplo-row') : [];
        const imDuplos = imTable ? imTable.querySelectorAll('.duplo-row') : [];

        let rowSample = `<tr><td class="text-uppercase text-start ps-2" colspan="2" style="width: 30%;">SAMPLE ID</td>`;
        let rowDate = `<tr><td class="text-uppercase text-start ps-2" colspan="2">DATE</td>`; // <-- Baris Date yang baru
        let rowDish = `<tr><td class="text-uppercase text-start ps-2" colspan="2">DISH NO.</td>`;
        let rowM1 = `<tr><td class="text-start ps-2">M1</td><td class="text-center">g</td>`;
        let rowM2 = `<tr><td class="text-start ps-2">M2</td><td class="text-center">g</td>`;
        let rowM2M1 = `<tr><td class="text-start ps-2">M2-M1</td><td class="text-center">g</td>`;
        let rowM3 = `<tr><td class="text-start ps-2">M3<sub>1</sub></td><td class="text-center">g</td>`;
        let rowM3M1 = `<tr><td class="text-start ps-2">M3<sub>1</sub>-M1</td><td class="text-center">g</td>`;
        let rowAshAdb = `<tr><td class="text-start ps-2">%ASH</td><td class="text-center">adb</td>`;
        let rowMoisture = `<tr><td class="text-start ps-2">%Moisture</td><td class="text-center">adb</td>`;
        let rowAshDb = `<tr><td class="text-start ps-2">%ASH</td><td class="text-center">db</td>`;
        let rowDiff = `<tr><td class="text-start ps-2" colspan="2">Absolute difference ,db</td>`;
        let rowAvg = `<tr><td class="text-uppercase text-start ps-2 fw-bold" colspan="2">AVERAGE % ASH, adb</td>`;

        for (let i = 0; i < simplos.length; i++) {
            const getTVal = (row, selector) => {
                let el = row ? row.querySelector(selector) : null;
                return el ? el.value || el.innerText : '';
            };

            let dateVal = getTVal(simplos[i], 'input[type="date"]'); // <-- Tarik tanggal spesifik
            let dish1 = getTVal(simplos[i], '.in-dish-1'); let dish2 = getTVal(duplos[i], '.in-dish-2');
            let m1_1 = getTVal(simplos[i], '.in-m1-1');    let m1_2 = getTVal(duplos[i], '.in-m1-2');
            let m2_1 = getTVal(simplos[i], '.in-m2-1');    let m2_2 = getTVal(duplos[i], '.in-m2-2');
            let m2m1_1 = getTVal(simplos[i], '.in-m2m1-1');let m2m1_2 = getTVal(duplos[i], '.in-m2m1-2');
            let m3_1 = getTVal(simplos[i], '.in-m3-1');    let m3_2 = getTVal(duplos[i], '.in-m3-2');
            let m3m1_1 = getTVal(simplos[i], '.in-m3m1-1');let m3m1_2 = getTVal(duplos[i], '.in-m3m1-2');
            
            let ash_adb_1 = parseFloat(getTVal(simplos[i], '.in-hasil-1')) || 0;
            let ash_adb_2 = parseFloat(getTVal(duplos[i], '.in-hasil-2')) || 0;
            
            let m_adb_1 = 0; let m_adb_2 = 0;
            if (imSimplos[i]) m_adb_1 = parseFloat(getTVal(imSimplos[i], '.in-hasil-1')) || 0;
            if (imDuplos[i]) m_adb_2 = parseFloat(getTVal(imDuplos[i], '.in-hasil-2')) || 0;

            let ash_db_1 = m_adb_1 < 100 ? (100 / (100 - m_adb_1)) * ash_adb_1 : 0;
            let ash_db_2 = m_adb_2 < 100 ? (100 / (100 - m_adb_2)) * ash_adb_2 : 0;
            
            let diff_db = Math.abs(ash_db_1 - ash_db_2);
            let avg_adb = (ash_adb_1 + ash_adb_2) / 2;
            
            rowSample += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold text-center">${i===0 ? sampleId : ''}</div></td>`;
            rowDate += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold text-center">${dateVal}</div></td>`; // <-- Masukkan tanggal
            rowDish += `<td><div contenteditable="true" class="print-editable w-100">${dish1}</div></td><td><div contenteditable="true" class="print-editable w-100">${dish2}</div></td>`;
            rowM1 += `<td><div contenteditable="true" class="print-editable w-100">${m1_1}</div></td><td><div contenteditable="true" class="print-editable w-100">${m1_2}</div></td>`;
            rowM2 += `<td><div contenteditable="true" class="print-editable w-100">${m2_1}</div></td><td><div contenteditable="true" class="print-editable w-100">${m2_2}</div></td>`;
            rowM2M1 += `<td><div contenteditable="true" class="print-editable w-100">${m2m1_1}</div></td><td><div contenteditable="true" class="print-editable w-100">${m2m1_2}</div></td>`;
            rowM3 += `<td><div contenteditable="true" class="print-editable w-100">${m3_1}</div></td><td><div contenteditable="true" class="print-editable w-100">${m3_2}</div></td>`;
            rowM3M1 += `<td><div contenteditable="true" class="print-editable w-100">${m3m1_1}</div></td><td><div contenteditable="true" class="print-editable w-100">${m3m1_2}</div></td>`;
            
            rowAshAdb += `<td><div contenteditable="true" class="print-editable w-100">${ash_adb_1 > 0 ? ash_adb_1.toFixed(2) : ''}</div></td><td><div contenteditable="true" class="print-editable w-100">${ash_adb_2 > 0 ? ash_adb_2.toFixed(2) : ''}</div></td>`;
            rowMoisture += `<td><div contenteditable="true" class="print-editable w-100">${m_adb_1 > 0 ? m_adb_1.toFixed(2) : ''}</div></td><td><div contenteditable="true" class="print-editable w-100">${m_adb_2 > 0 ? m_adb_2.toFixed(2) : ''}</div></td>`;
            rowAshDb += `<td><div contenteditable="true" class="print-editable w-100">${ash_db_1 > 0 ? ash_db_1.toFixed(2) : ''}</div></td><td><div contenteditable="true" class="print-editable w-100">${ash_db_2 > 0 ? ash_db_2.toFixed(2) : ''}</div></td>`;
            
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100">${diff_db > 0 ? diff_db.toFixed(2) : ''}</div></td><td><div contenteditable="true" class="print-editable w-100 text-danger">Yes/No</div></td>`;
            rowAvg += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${avg_adb > 0 ? avg_adb.toFixed(2) : ''}</div></td>`;
        }

        // 4. Gabungkan dan Tampilkan Semua Baris
        let finalHtml = rowDate + `</tr>` + rowSample + `</tr>` + rowDish + `</tr>` + rowM1 + `</tr>` + rowM2 + `</tr>` + rowM2M1 + `</tr>` + rowM3 + `</tr>` + rowM3M1 + `</tr>` + rowAshAdb + `</tr>` + rowMoisture + `</tr>` + rowAshDb + `</tr>` + rowDiff + `</tr>` + rowAvg + `</tr>`;
        
        document.getElementById('print-ash-dynamic-tbody').innerHTML = finalHtml;
    }

    function syncGcvData() {
        const gcvTable = document.querySelector('table.param-table[data-code="GCV"]');
        if (!gcvTable) {
            console.warn("Tabel GCV tidak ditemukan di Tab 1.");
            return;
        }
        
        const pid = gcvTable.getAttribute('data-pid');
        
        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-gcv-ref-no').innerText = getVal(`input[name="params[${pid}][ref_no]"]`);
        document.getElementById('print-gcv-cal-id').innerText = getVal(`input[name="params[${pid}][furnace_id]"]`);

        const firstDateEl = gcvTable.querySelector('input[type="date"]');
        document.getElementById('print-gcv-date').innerText = firstDateEl ? firstDateEl.value : getVal('input[name="tanggal_terima"]');
        document.getElementById('print-gcv-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-gcv-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);
        
        const sampleId = getVal('input[name="kode_sampel"]');

        let imValue = 0;
        const imTable = document.querySelector('table.param-table[data-code="IM"]');
        if (imTable) {
            const imAvgEl = imTable.querySelector('.out-avg-adb');
            if (imAvgEl && !isNaN(parseFloat(imAvgEl.innerText))) {
                imValue = parseFloat(imAvgEl.innerText);
            }
        }

        const simplos = gcvTable.querySelectorAll('.simplo-row');
        const duplos = gcvTable.querySelectorAll('.duplo-row');
        
        let rowDate = `<tr><td class="text-uppercase text-start ps-2" colspan="2" style="width: 25%;">DATE</td>`;
        let rowSample = `<tr><td class="text-uppercase text-start ps-2" colspan="2">SAMPLE ID</td>`;
        let rowVessel = `<tr><td class="text-uppercase text-start ps-2" colspan="2">VESSEL NO.</td>`;
        let rowCall = `<tr><td class="text-uppercase text-start ps-2" colspan="2">CALL ID</td>`;
        let rowWc = `<tr><td class="ps-2" colspan="2">Weight of crucible</td>`;
        let rowWcs = `<tr><td class="ps-2" colspan="2">Weight of crucible + sample</td>`;
        let rowMass = `<tr><td class="text-uppercase text-start ps-2" colspan="2">SAMPLE MASS</td>`;
        let rowPre = `<tr><td class="ps-2" colspan="2">Preliminary Result</td>`;
        let rowEe = `<tr><td class="ps-2" colspan="2">Ee</td>`;
        let rowT = `<tr><td class="ps-2" colspan="2">t</td>`;
        let rowVt = `<tr><td class="ps-2" colspan="2">Volume of titrant</td>`;
        let rowNt = `<tr><td class="ps-2" colspan="2">Normality of titrant</td>`;
        let rowE1 = `<tr><td class="ps-2" colspan="2">e1</td>`;
        let rowLf = `<tr><td class="ps-2" colspan="2">Length of fuse</td>`;
        let rowHf = `<tr><td class="ps-2" colspan="2">Heat of combustion of fuse</td>`;
        let rowE2 = `<tr><td class="ps-2" colspan="2">e2</td>`;
        let rowTs = `<tr><td class="ps-2" colspan="2">Total sulfur</td>`;
        let rowE3 = `<tr><td class="ps-2" colspan="2">e3</td>`;
        let rowAid = `<tr><td class="ps-2" colspan="2">Aid Combustion Mass</td>`;
        let rowE4 = `<tr><td class="ps-2" colspan="2">e4</td>`;
        let rowFinalAdb = `<tr><td class="text-uppercase text-start ps-2" colspan="2">FINAL RESULT, adb</td>`;
        let rowImAdb = `<tr><td class="ps-2" colspan="2">Moisture in the analysis sample %, adb</td>`;
        let rowFinalDb = `<tr><td class="text-uppercase text-start ps-2" colspan="2">FINAL RESULT, db</td>`;
        let rowDiff = `<tr><td class="ps-2" colspan="2">Absolute Difference , db</td>`;
        let rowAvg = `<tr><td class="text-uppercase text-start ps-2" colspan="2">AVERAGE (cal/g), adb</td>`;

        for (let i = 0; i < simplos.length; i++) {
            const getTVal = (row, selector) => {
                let el = row ? row.querySelector(selector) : null;
                if(el && el.tagName === 'SELECT') return el.options[el.selectedIndex].text;
                return el ? el.value || el.innerText : '';
            };

            let dateVal = getTVal(simplos[i], 'input[type="date"]');
            let diffVal = getTVal(simplos[i], '.out-diff');
            let yesnoVal = getTVal(simplos[i], 'select[name*="[yesno]"]');
            let avgVal = getTVal(simplos[i], '.out-avg-adb'); 
            
            let fAdbS = parseFloat(getTVal(simplos[i], '.in-hasil-1')) || 0;
            let fAdbD = parseFloat(getTVal(duplos[i], '.in-hasil-2')) || 0;
            let fDbS = (100 / (100 - imValue)) * fAdbS;
            let fDbD = (100 / (100 - imValue)) * fAdbD;
            let v_fDbS = isNaN(fDbS) ? '-' : fDbS.toFixed(2);
            let v_fDbD = isNaN(fDbD) ? '-' : fDbD.toFixed(2);

            rowDate += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${dateVal}</div></td>`;
            rowSample += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold text-center">${sampleId}</div></td>`;
            
            rowVessel += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-bomb-1')}</div></td>`;
            rowVessel += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-bomb-2')}</div></td>`;
            
            rowCall += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-call-1')}</div></td>`;
            rowCall += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-call-2')}</div></td>`;
            
            rowWc += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-wc-1')}</div></td>`;
            rowWc += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-wc-2')}</div></td>`;
            
            rowWcs += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-wcs-1')}</div></td>`;
            rowWcs += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-wcs-2')}</div></td>`;
            
            rowMass += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-mass-1')}</div></td>`;
            rowMass += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-mass-2')}</div></td>`;
            
            rowPre += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-pre-1')}</div></td>`;
            rowPre += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-pre-2')}</div></td>`;
            
            rowEe += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-ee-1')}</div></td>`;
            rowEe += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-ee-2')}</div></td>`;
            
            rowT += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-t-1')}</div></td>`;
            rowT += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-t-2')}</div></td>`;
            
            rowVt += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-vt-1')}</div></td>`;
            rowVt += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-vt-2')}</div></td>`;
            
            rowNt += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-nt-1')}</div></td>`;
            rowNt += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-nt-2')}</div></td>`;
            
            rowE1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-e1-1')}</div></td>`;
            rowE1 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-e1-2')}</div></td>`;
            
            rowLf += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-lf-1')}</div></td>`;
            rowLf += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-lf-2')}</div></td>`;
            
            rowHf += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-hf-1')}</div></td>`;
            rowHf += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-hf-2')}</div></td>`;
            
            rowE2 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-e2-1')}</div></td>`;
            rowE2 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-e2-2')}</div></td>`;
            
            rowTs += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-ts-1')}</div></td>`;
            rowTs += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-ts-2')}</div></td>`;
            
            rowE3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-e3-1')}</div></td>`;
            rowE3 += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-e3-2')}</div></td>`;
            
            rowAid += `<td class="text-center">-</td><td class="text-center">-</td>`;
            rowE4 += `<td class="text-center">-</td><td class="text-center">-</td>`;
            
            rowFinalAdb += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(simplos[i], '.in-hasil-1')}</div></td>`;
            rowFinalAdb += `<td><div contenteditable="true" class="print-editable w-100">${getTVal(duplos[i], '.in-hasil-2')}</div></td>`;
            
            rowImAdb += `<td colspan="2" class="text-center"><div contenteditable="true" class="print-editable w-100">${imValue ? imValue.toFixed(2) : '-'}</div></td>`;
            
            rowFinalDb += `<td><div contenteditable="true" class="print-editable w-100 fw-bold">${v_fDbS}</div></td>`;
            rowFinalDb += `<td><div contenteditable="true" class="print-editable w-100 fw-bold">${v_fDbD}</div></td>`;
            
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100">${diffVal !== '-' ? diffVal : ''}</div></td>`;
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100">${yesnoVal || 'YES/NO'}</div></td>`;

            rowAvg += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${avgVal !== '-' ? avgVal : ''}</div></td>`;
        }

        rowDate += `</tr>`; rowSample += `</tr>`; rowVessel += `</tr>`; rowCall += `</tr>`; 
        rowWc += `</tr>`; rowWcs += `</tr>`; rowMass += `</tr>`; rowPre += `</tr>`; 
        rowEe += `</tr>`; rowT += `</tr>`; rowVt += `</tr>`; rowNt += `</tr>`; 
        rowE1 += `</tr>`; rowLf += `</tr>`; rowHf += `</tr>`; rowE2 += `</tr>`; 
        rowTs += `</tr>`; rowE3 += `</tr>`; rowAid += `</tr>`; rowE4 += `</tr>`;
        rowFinalAdb += `</tr>`; rowImAdb += `</tr>`; rowFinalDb += `</tr>`; 
        rowDiff += `</tr>`; rowAvg += `</tr>`;

        document.getElementById('print-gcv-dynamic-tbody').innerHTML = rowDate + rowSample + rowVessel + rowCall + rowWc + rowWcs + rowMass + rowPre + rowEe + rowT + rowVt + rowNt + rowE1 + rowLf + rowHf + rowE2 + rowTs + rowE3 + rowAid + rowE4 + rowFinalAdb + rowImAdb + rowFinalDb + rowDiff + rowAvg;
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
            
        }
    }
</script>
