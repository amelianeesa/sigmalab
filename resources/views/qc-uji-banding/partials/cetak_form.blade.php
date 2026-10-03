<div class="p-2 p-md-4 bg-light">
    <div class="row mb-3 no-print g-2">
        <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Pilih Modul Cetak</label>
            <select id="cetak-modul-selector" class="form-select border-primary shadow-sm">
                <option value="">-- Pilih Modul --</option>
                <option value="proximate">Proximate Analysis</option>
                <option value="im">Determination of Moisture in Analysis Sample</option>
                <option value="ash">Determination of Ash Content</option>
                <option value="vm">Determination of Volatile Matter</option>
                <option value="sulfur">Determination of Sulfur by IR Spectrometry</option>
                <option value="gcv">Determination of Gross Calorific Value</option>
                <option value="tm">Determination of Total Moisture</option>
                <option value="chn">Determination of Carbon, Hydrogen, Nitrogen by Instrument</option>
                <option value="ncv">Determination of Net Calorific Value</option>
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
                @include('qc-uji-banding.print.modules.tm')
                @include('qc-uji-banding.print.modules.chn')
                @include('qc-uji-banding.print.modules.ncv')
            </div>
        </div>
    </div>
</div>

<style>

    #print-prox-header-0 th, #print-prox-header-0 td,
    #print-prox-header-1 th, #print-prox-header-1 td,
    #print-prox-header-2 th, #print-prox-header-2 td {
        font-weight: normal !important;
    }
    
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
            document.getElementById('print-placeholder').style.display = 'none';
            document.querySelectorAll('.print-module-container').forEach(el => el.style.display = 'none');
            
            const selected = this.value;
            if (!selected) {
                document.getElementById('print-placeholder').style.display = 'block';
                return;
            }

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
                } else if (selected === 'tm') {
                    syncTmdata();
                } else if (selected === 'chn') {
                    syncChnData();
                } else if (selected === 'ncv') {
                    syncNcvData();
                }
            }
            setTimeout(fitPreview, 50);
        });

        const tabCetak = document.getElementById('tab-cetak-form-tab');
        if (tabCetak) {
            tabCetak.addEventListener('shown.bs.tab', function () {
    
                if (modulSelector && modulSelector.value) {
                    modulSelector.dispatchEvent(new Event('change'));
                }
            });
        }
    });

    let printMarker = null;

    window.addEventListener('beforeprint', () => {
        const area = document.getElementById('print-area');
        if (!area) return;

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

        vp.style.height = (scaler.scrollHeight * scale) + 'px';
    }

    window.addEventListener('load', fitPreview);
    window.addEventListener('resize', fitPreview);

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
            rowMass += `<td><div contenteditable="true" class="print-editable w-100 text-center">${mass1}</div></td><td><div contenteditable="true" class="print-editable w-100 text-center">${mass2}</div></td>`;
            rowTsAdb += `<td><div contenteditable="true" class="print-editable w-100 text-center">${fmt(ts_adb_1)}</div></td><td><div contenteditable="true" class="print-editable w-100 text-center">${fmt(ts_adb_2)}</div></td>`;
            rowAvgAdb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold text-center">${fmt(avg_adb)}</div></td>`;
            
            rowRepLimit += `<td colspan="2" class="align-middle p-1">
                ${selectRumus}
                <div contenteditable="true" class="print-editable w-100 text-center fw-bold result-toleransi"></div>
            </td>`;
            
            rowMoisture += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${fmt(m_adb)}</div></td>`;
            rowFinalDb += `<td><div contenteditable="true" class="print-editable w-100 text-center">${fmt(final_db_1)}</div></td><td><div contenteditable="true" class="print-editable w-100 text-center">${fmt(final_db_2)}</div></td>`;
            rowAvgDb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold text-center">${fmt(avg_db)}</div></td>`;
            rowDiff += `<td><div contenteditable="true" class="print-editable w-100 text-center">${fmt(diff_db)}</div></td><td><div contenteditable="true" class="print-editable w-100 text-center text-danger">YES/NO)*</div></td>`;
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

        const getVal = (selector) => {
            let el = document.querySelector(selector);
            return el ? el.value : '';
        };

        document.getElementById('print-vm-ref-no').innerText = getVal(`input[name="params[${pid}][ref_no]"]`);
        document.getElementById('print-vm-furnace-id').innerText = getVal(`input[name="params[${pid}][furnace_id]"]`);
        document.getElementById('print-vm-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);

        document.getElementById('print-vm-meta-date').innerText = getVal('input[name="tanggal_terima"]'); // Atau sesuaikan dengan input date di form
        document.getElementById('print-vm-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-vm-temp').innerText = getVal(`input[name="params[${pid}][indicate_t]"]`);
        
        const sampleId = getVal('input[name="kode_sampel"]');

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

            let mAdb = getTVal(simplos[i], '.in-im-1');
            rowMadb += `<td colspan="2"><div contenteditable="true" class="print-editable w-100 fw-bold">${mAdb}</div></td>`;

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

            let hasilDuplo = duplos[i] ? duplos[i].querySelector('.in-hasil-2') : null;
            let mDuplo = hasilDuplo ? (hasilDuplo.value || hasilDuplo.innerText) : '';

            rowM += `<td><div contenteditable="true" class="print-editable w-100">${mSimplo}</div></td>`;
            rowM += `<td><div contenteditable="true" class="print-editable w-100">${mDuplo}</div></td>`;

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

        let finalHtml = rowDate + `</tr>` + rowSample + `</tr>` + rowDish + `</tr>` + rowM1 + `</tr>` + rowM2 + `</tr>` + rowM2M1 + `</tr>` + rowM3 + `</tr>` + rowM3M1 + `</tr>` + rowAshAdb + `</tr>` + rowMoisture + `</tr>` + rowAshDb + `</tr>` + rowDiff + `</tr>` + rowAvg + `</tr>`;
        
        document.getElementById('print-ash-dynamic-tbody').innerHTML = finalHtml;
    }

    function syncChnData() {
        try {
            const table = document.querySelector('table[data-code="CHN"]');
            if(!table) return;

            const pid = table.getAttribute('data-pid');
            
            const getValSafe = (selector) => {
                try {
                    let el = document.querySelector(selector);
                    return el ? el.value : '';
                } catch (e) {
                    return '';
                }
            };

            const setPrintText = (id, selector) => {
                const el = document.getElementById(id);
                if (el) el.innerText = getValSafe(selector);
            };

            setPrintText('print-chn-ref-no', `input[name="params[${pid}][ref_no]"]`);
            setPrintText('print-chn-instrument-id', `input[name="params[${pid}][furnace_id]"]`);
            setPrintText('print-chn-std-method', `input[name="params[${pid}][std_method]"]`);
            setPrintText('print-chn-balance-id', `input[name="params[${pid}][blnc_id]"]`);

            // Tarik otomatis kode sampel dari input form utama
            const sampleId = getValSafe('input[name="kode_sampel"]');

            const printTbody = document.getElementById('print-chn-dynamic-tbody');
            if(!printTbody) return;

            const fmt = (val) => {
                if (val === undefined || val === null || val === '' || val === '-') return val || '';
                let num = parseFloat(val);
                return isNaN(num) ? val : num.toFixed(2);
            };

            const tbodies = table.querySelectorAll('tbody');
            const imTable = document.querySelector('table.param-table[data-code="IM"]');
            const imTbodies = imTable ? imTable.querySelectorAll('tbody.proximate-tbody') : [];

            // --- PERUBAHAN SUSUNAN BARIS ATAS ---
            let rowDate = `<tr><td class="text-center fw-bold">DATE</td>`;
            let rowSample = `<tr><td class="text-center fw-bold">SAMPLE ID</td>`;
            let rowHeader = `<tr><td class="text-center fw-bold align-middle" style="width: 25%;">PARAMETER</td>`;
            // -----------------------------------
            
            let rowWeight = `<tr><td class="text-start ps-2">Weight (gram)</td>`;
            let rowC_adb = `<tr><td class="text-start ps-2">Carbon %adb</td>`;
            let rowH_analyzed = `<tr><td class="text-start ps-2">H as Analyzed %</td>`;
            let rowM_ad = `<tr><td class="text-start ps-2">Moisture % ad</td>`;
            let rowH_coal = `<tr><td class="text-start ps-2">H in coal % ad</td>`;
            let rowN_ad = `<tr><td class="text-start ps-2">Nitrogen % ad</td>`;
            
            let rowC_db = `<tr><td class="text-start ps-2">Carbon % db</td>`;
            let rowC_diff = `<tr><td class="text-start ps-2">Absolute difference</td>`;
            
            let rowH_db = `<tr><td class="text-start ps-2">Hydrogen, % db</td>`;
            let rowH_diff = `<tr><td class="text-start ps-2">Absolute difference</td>`;
            
            let rowN_db = `<tr><td class="text-start ps-2">Nitrogen % db</td>`;
            let rowN_diff = `<tr><td class="text-start ps-2">Absolute difference</td>`;

            tbodies.forEach((tbody, idx) => {
                const dateEl = tbody.querySelector('input[type="date"]');
                const dateVal = dateEl ? dateEl.value : '';

                // Memasukkan nilai Date, Sample, dan Header menyesuaikan jumlah pengujian
                rowDate += `<td colspan="3"><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${dateVal}</div></td>`;
                rowSample += `<td colspan="3"><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${sampleId}</div></td>`;
                rowHeader += `<td class="text-center fw-bold">Simplo</td><td class="text-center fw-bold">Duplo</td><td class="text-center fw-bold">Average</td>`;

                const getRowData = (type) => {
                    const row = tbody.querySelector(`tr[data-type="${type}"]`);
                    if(!row) return { d1: '', d2: '', diff: '', avg: '', yn: 'YES/NO)*' };
                    let ynSelect = row.querySelector('select');
                    return {
                        d1: fmt(row.querySelector('input[class*="-1"]:not([type="hidden"])')?.value),
                        d2: fmt(row.querySelector('input[class*="-2"]:not([type="hidden"])')?.value),
                        diff: fmt(row.querySelector('.out-diff')?.textContent),
                        avg: fmt(row.querySelector('.out-avg-txt')?.textContent),
                        yn: ynSelect && ynSelect.value ? ynSelect.value : 'YES/NO)*'
                    };
                };

                const w = getRowData('weight');
                const c = getRowData('carbon');
                const h = getRowData('hydrogen');
                const n = getRowData('nitrogen');

                let m_adb = 0;
                if (imTbodies[idx]) {
                    let m_el = imTbodies[idx].querySelector('.out-avg-adb');
                    m_adb = m_el ? (parseFloat(m_el.innerText) || 0) : 0;
                }

                const calcAdb = (dbVal) => {
                    let val = parseFloat(dbVal);
                    if (isNaN(val) || m_adb >= 100) return '';
                    return (val * ((100 - m_adb) / 100)).toFixed(2);
                };

                const calcHAnalyzed = (dbVal) => {
                    let val = parseFloat(dbVal);
                    if (isNaN(val) || m_adb >= 100) return '';
                    let hInCoal = val * ((100 - m_adb) / 100);
                    return (hInCoal + (0.1119 * m_adb)).toFixed(2);
                };

                let m_str = m_adb > 0 ? m_adb.toFixed(2) : '';

                const td3 = (v1, v2, v3) => `
                    <td><div contenteditable="true" class="print-editable w-100 text-center">${v1}</div></td>
                    <td><div contenteditable="true" class="print-editable w-100 text-center">${v2}</div></td>
                    <td><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${v3}</div></td>
                `;
                
                const tdDiff = (diff, yn) => `
                    <td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${diff}</div></td>
                    <td><div contenteditable="true" class="print-editable w-100 text-center text-danger">${yn}</div></td>
                `;

                rowWeight += td3(w.d1, w.d2, w.avg);
                rowC_adb += td3(calcAdb(c.d1), calcAdb(c.d2), calcAdb(c.avg));
                rowH_analyzed += td3(calcHAnalyzed(h.d1), calcHAnalyzed(h.d2), calcHAnalyzed(h.avg));
                rowM_ad += td3(m_str, m_str, m_str);
                rowH_coal += td3(calcAdb(h.d1), calcAdb(h.d2), calcAdb(h.avg));
                rowN_ad += td3(calcAdb(n.d1), calcAdb(n.d2), calcAdb(n.avg));

                rowC_db += td3(c.d1, c.d2, c.avg);
                rowC_diff += tdDiff(c.diff, c.yn);
                
                rowH_db += td3(h.d1, h.d2, h.avg);
                rowH_diff += tdDiff(h.diff, h.yn);

                rowN_db += td3(n.d1, n.d2, n.avg);
                rowN_diff += tdDiff(n.diff, n.yn);
            });

            rowDate += `</tr>`;
            rowSample += `</tr>`;
            rowHeader += `</tr>`;
            rowWeight += `</tr>`;
            rowC_adb += `</tr>`;
            rowH_analyzed += `</tr>`;
            rowM_ad += `</tr>`;
            rowH_coal += `</tr>`;
            rowN_ad += `</tr>`;
            rowC_db += `</tr>`;
            rowC_diff += `</tr>`;
            rowH_db += `</tr>`;
            rowH_diff += `</tr>`;
            rowN_db += `</tr>`;
            rowN_diff += `</tr>`;

            // URUTAN CETAK: Date, lalu Sample, lalu Parameter Header, lalu baris data
            printTbody.innerHTML = rowDate + rowSample + rowHeader + rowWeight + rowC_adb + rowH_analyzed + rowM_ad + rowH_coal + rowN_ad + rowC_db + rowC_diff + rowH_db + rowH_diff + rowN_db + rowN_diff;
        } catch (error) {
            console.error("Terjadi error saat sinkronisasi CHN:", error);
        }
    }

    
    function syncGcvData() {
        const gcvTable = document.querySelector('table[data-code="GCV"]');
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
        document.getElementById('print-gcv-std-method').innerText = getVal(`input[name="params[${pid}][std_method]"]`);
        document.getElementById('print-gcv-balance-id').innerText = getVal(`input[name="params[${pid}][blnc_id]"]`);
        
        const dateInput = gcvTable.querySelector('input[type="date"]');
        document.getElementById('print-gcv-date').innerText = dateInput ? dateInput.value : getVal('input[name="tanggal_terima"]');

        const sampleId = getVal('input[name="kode_sampel"]');
        const printTbody = document.getElementById('print-gcv-dynamic-tbody');
        printTbody.innerHTML = ''; // Bersihkan isi sebelumnya

        const simplos = gcvTable.querySelectorAll('.simplo-row');
        const duplos = gcvTable.querySelectorAll('.duplo-row');

        let html = '';
        for (let i = 0; i < simplos.length; i++) {
            const s = simplos[i];
            const d = duplos[i];
            
            const v = (row, sel) => {
                const el = row ? row.querySelector(sel) : null;
                return el ? (el.value || el.innerText).trim() : '';
            };

            const rowsData = [
                { label: 'BOMB NO', s: v(s, '.in-bomb-1'), d: v(d, '.in-bomb-2') },
                { label: 'Call ID', s: v(s, '.in-call-1'), d: v(d, '.in-call-2') },
                { label: 'Weight of Crucible', s: v(s, '.in-wc-1'), d: v(d, '.in-wc-2') },
                { label: 'Weight of Crucible + Sample', s: v(s, '.in-wcs-1'), d: v(d, '.in-wcs-2') },
                { label: 'Sample Mass', s: v(s, '.in-mass-1'), d: v(d, '.in-mass-2') },
                { label: 'Preliminary Result', s: v(s, '.in-pre-1'), d: v(d, '.in-pre-2') },
                { label: 'Ee', s: v(s, '.in-ee-1'), d: v(d, '.in-ee-2') },
                { label: 't', s: v(s, '.in-t-1'), d: v(d, '.in-t-2') },
                { label: 'Volume of Titrant', s: v(s, '.in-vt-1'), d: v(d, '.in-vt-2') },
                { label: 'Normality of Titrant', s: v(s, '.in-nt-1'), d: v(d, '.in-nt-2') },
                { label: 'e1', s: v(s, '.in-e1-1'), d: v(d, '.in-e1-2') },
                { label: 'Length of Fuse', s: v(s, '.in-lf-1'), d: v(d, '.in-lf-2') },
                { label: 'Heat of Comb. of Fuse', s: v(s, '.in-hf-1'), d: v(d, '.in-hf-2') },
                { label: 'e2', s: v(s, '.in-e2-1'), d: v(d, '.in-e2-2') },
                { label: 'Total Sulfur', s: v(s, '.in-ts-1'), d: v(d, '.in-ts-2') },
                { label: 'e3', s: v(s, '.in-e3-1'), d: v(d, '.in-e3-2') },
                { label: 'Aid Combustion Mass', s: '-', d: '-' },
                { label: 'e4', s: '-', d: '-' },
                { label: 'Final Result', s: v(s, '.in-hasil-1'), d: v(d, '.in-hasil-2') }
            ];

            let diff = v(s, '.out-diff');
            let yn = v(s, 'select[name*="[yesno]"]');
            let avg = v(s, '.out-avg-adb');

            html += `
                <tr>
                    <td class="text-center fw-bold" style="width: 40%;">SAMPLE ID</td>
                    <td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${i === 0 ? sampleId : ''}</div></td>
                </tr>
            `;

            rowsData.forEach(item => {
                html += `
                <tr>
                    <td class="text-center">${item.label}</td>
                    <td style="width: 30%;"><div contenteditable="true" class="print-editable w-100 text-center">${item.s}</div></td>
                    <td style="width: 30%;"><div contenteditable="true" class="print-editable w-100 text-center">${item.d}</div></td>
                </tr>
                `;
            });

            html += `
                <tr class="fw-bold">
                    <td class="text-center">ABSOLUTE DIFFERENCE</td>
                    <td><div contenteditable="true" class="print-editable w-100 text-center">${diff !== '-' ? diff : ''}</div></td>
                    <td><div contenteditable="true" class="print-editable w-100 text-center text-danger">${yn || 'YES/NO)*'}</div></td>
                </tr>
                <tr class="fw-bold bg-warning bg-opacity-10">
                    <td class="text-center">AVERAGE %</td>
                    <td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${avg !== '-' ? avg : ''}</div></td>
                </tr>
            `;
        }

        printTbody.innerHTML = html;
    }

    function syncTmdata() {
        const tmTable = document.querySelector('table.input-table[data-code="TM"]');
        if (!tmTable) {
            console.warn("Tabel Total Moisture (TM) tidak ditemukan di Tab 1.");
            return;
        }

        const getMeta = (key) => {
            const el = document.querySelector(`.tm-meta[data-meta="${key}"]`);
            return el ? el.value : '';
        };

        document.getElementById('print-tm-ref-no').innerText = getMeta('reference_no');
        document.getElementById('print-tm-balance-id').innerText = getMeta('blnc_id');
        document.getElementById('print-tm-std-method').innerText = getMeta('std_method');
        document.getElementById('print-tm-oven-id').innerText = getMeta('oven_id');

        const sampleEl = document.querySelector('input[name="kode_sampel"]');
        const sampleId = sampleEl ? sampleEl.value : '';

        const simplos = tmTable.querySelectorAll('tr.row-entry[data-type="simplo"]');
        const duplos  = tmTable.querySelectorAll('tr.row-entry[data-type="duplo"]');

        const val = (row, sel) => {
            const el = row ? row.querySelector(sel) : null;
            if (!el) return '';
            if (el.tagName === 'SELECT') return el.value;
            const v = (el.value !== undefined && el.tagName === 'INPUT') ? el.value : el.innerText;
            return (v || '').trim();
        };
        
        const fmt = v => (v !== '' && v !== '-' && !isNaN(parseFloat(v))) ? parseFloat(v).toFixed(2) : '-';
        
        const cell = (v, extra = '') =>
            `<td><div contenteditable="true" class="print-editable w-100 ${extra}">${v}</div></td>`;
        
        const cell2 = (v, extra = '') =>
            `<td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center ${extra}">${v}</div></td>`;

        let rowDate   = `<tr><td class="text-uppercase text-start ps-2" colspan="2" style="width:30%;">DATE</td>`;
        let rowSample = `<tr><td class="text-uppercase text-start ps-2" colspan="2">SAMPLE ID</td>`;
        let rowAdl1   = `<tr><td class="text-uppercase text-start ps-2" colspan="2">% ADL1</td>`;
        let rowAdl2   = `<tr><td class="text-uppercase text-start ps-2" colspan="2">% ADL2</td>`;
        let rowRm     = `<tr><td class="text-uppercase text-start ps-2" colspan="2">% RM</td>`;
        let rowTmP    = `<tr><td class="text-uppercase text-start ps-2" colspan="2">% TM'</td>`;
        let rowTm     = `<tr><td class="text-uppercase text-start ps-2" colspan="2">% TM</td>`;
        let rowDiff   = `<tr><td class="text-uppercase text-start ps-2" colspan="2">Absolute Difference</td>`;
        let rowAvg    = `<tr><td class="text-uppercase text-start ps-2 fw-bold" colspan="2">AVERAGE % TM</td>`;

        for (let i = 0; i < simplos.length; i++) {
            const s = simplos[i], d = duplos[i];

            const adl1_s = val(s, '.in-adl1'), adl1_d = val(d, '.in-adl1');
            const tmP_s = adl1_s ? val(s, '.in-tm-prime') : '';
            const tmP_d = adl1_d ? val(d, '.in-tm-prime') : '';

            const tm_s = val(s, '.out-tm'), tm_d = val(d, '.out-tm');
            const diff = val(s, '.out-abs');
            const yn   = val(s, 'select[name*="[yesno]"]');
            const avg  = val(s, '.out-avg-ar');

            rowDate   += cell2(val(s, 'input[type="date"]'), 'fw-bold');
            rowSample += cell2(i === 0 ? sampleId : '', 'fw-bold');
            rowAdl1   += cell(adl1_s || '-') + cell(adl1_d || '-');
            rowAdl2   += cell(fmt(val(s, '.in-adl2'))) + cell(fmt(val(d, '.in-adl2')));
            rowRm     += cell(fmt(val(s, '.in-rm'))) + cell(fmt(val(d, '.in-rm')));
            rowTmP    += cell(fmt(tmP_s)) + cell(fmt(tmP_d));
            rowTm     += cell(fmt(tm_s), 'fw-bold') + cell(fmt(tm_d), 'fw-bold');
            rowDiff   += cell(diff && diff !== '-' ? diff : '') + cell(yn || 'YES/NO', 'text-danger');
            rowAvg    += cell2(avg && avg !== '-' ? avg : '', 'fw-bold');
        }

        let finalHtml = [rowDate, rowSample, rowAdl1, rowAdl2, rowRm, rowTmP, rowTm, rowDiff, rowAvg]
            .map(r => r + '</tr>').join('');
            
        document.getElementById('print-tm-dynamic-tbody').innerHTML = finalHtml;
    }

    function syncNcvData() {
        try {
            const ncvTable = document.querySelector('table[data-code="NCV"]');
            if(!ncvTable) return;

            const pid = ncvTable.getAttribute('data-pid');
            
            const getValSafe = (selector) => {
                try {
                    let el = document.querySelector(selector);
                    return el ? el.value : '';
                } catch (e) {
                    return '';
                }
            };

            const setPrintText = (id, selector) => {
                const el = document.getElementById(id);
                if (el) el.innerText = getValSafe(selector);
            };

            setPrintText('print-ncv-ref-no', `input[name="params[${pid}][ref_no]"]`);
            setPrintText('print-ncv-instrument-id', `input[name="params[${pid}][calorimeter_id]"]`);
            setPrintText('print-ncv-std-method', `input[name="params[${pid}][std_method]"]`);
            setPrintText('print-ncv-balance-id', `input[name="params[${pid}][blnc_id]"]`);
            
            const firstDateEl = ncvTable.querySelector('input[type="date"]');
            const topDateEl = document.getElementById('print-ncv-date');
            if (topDateEl) {
                topDateEl.innerText = firstDateEl ? firstDateEl.value : getValSafe('input[name="tanggal_terima"]');
            }

            const sampleId = getValSafe('input[name="kode_sampel"]');

            const printTbody = document.getElementById('print-ncv-dynamic-tbody');
            if(!printTbody) return;
            printTbody.innerHTML = '';

            const tbodies = ncvTable.querySelectorAll('tbody.generic-tbody');
            let html = '';

            tbodies.forEach((tbody, idx) => {
                const dateEl = tbody.querySelector('input[type="date"]');
                const dateVal = dateEl ? dateEl.value : '';

                // Baris simplo memuat hampir semua data, baris duplo memuat bomb 2, callid 2, dan qv 2
                const s = tbody.querySelector('.simplo-row');
                const d = tbody.querySelector('.duplo-row');
                if(!s) return;

                const v = (row, sel) => {
                    if (!row) return '';
                    const el = row.querySelector(sel);
                    return el ? (el.value || el.innerText || '').trim() : '';
                };

                const rowsData = [
                    { label: 'BOMB NO', s: v(s, '.in-bomb-1'), d: v(d, '.in-bomb-2'), type: 'split' },
                    { label: 'CALL ID', s: v(s, '.in-callid-1'), d: v(d, '.in-callid-2'), type: 'split' },
                    { label: 'Qv (ad) gross', s: v(s, '.in-qvad-1'), d: v(d, '.in-qvad-2'), type: 'split' },
                    { label: 'Avg. Qv (ad) gross', s: v(s, '.in-avgqv'), type: 'single' },
                    { label: 'Total Moisture', s: v(s, '.in-tm'), type: 'single' },
                    { label: 'Moisture in Analysis', s: v(s, '.in-im'), type: 'single' },
                    { label: 'Hydrogen (ad)', s: v(s, '.in-h'), type: 'single' },
                    { label: 'Nitrogen (ad)', s: v(s, '.in-n'), type: 'single' },
                    { label: 'Oxygen (ad)', s: v(s, '.in-o'), type: 'single' },
                    { label: 'R (Gas Constant at 25&deg;C)', s: v(s, '.in-r'), type: 'single' },
                    { label: 'T (K) at 25&deg;C', s: v(s, '.in-t'), type: 'single' },
                    { label: 'Hvap (constant pressure at 25&deg;C)', s: v(s, '.in-hvap'), type: 'single' },
                    { label: 'Qv &rarr; p', s: v(s, '.in-qvp'), type: 'single' },
                    { label: 'Qh', s: v(s, '.in-qh'), type: 'single' },
                    { label: 'Qm (ar)', s: v(s, '.in-qmar'), type: 'single' },
                    { label: 'Qv (ad) gross (J/g)', s: v(s, '.in-qvadj'), type: 'single' },
                    { label: 'Qpar (net) (J/g)', s: v(s, '.in-qparj'), type: 'single' },
                    { label: 'Qpar (net)', s: v(s, '.in-hasil-1'), type: 'final' }
                ];

                html += `
                    <tr>
                        <td class="text-center fw-bold">DATE</td>
                        <td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${dateVal}</div></td>
                    </tr>
                    <tr>
                        <td class="text-center fw-bold">SAMPLE ID</td>
                        <td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center fw-bold">${sampleId}</div></td>
                    </tr>
                    <tr>
                        <td class="text-center fw-bold align-middle" style="width: 40%;">PARAMETER</td>
                        <td class="text-center fw-bold" style="width: 30%;">Simplo</td>
                        <td class="text-center fw-bold" style="width: 30%;">Duplo</td>
                    </tr>
                `;

                rowsData.forEach(item => {
                    if (item.type === 'split') {
                        html += `
                            <tr>
                                <td class="text-start ps-3">${item.label}</td>
                                <td><div contenteditable="true" class="print-editable w-100 text-center">${item.s}</div></td>
                                <td><div contenteditable="true" class="print-editable w-100 text-center">${item.d}</div></td>
                            </tr>
                        `;
                    } else if (item.type === 'single') {
                        html += `
                            <tr>
                                <td class="text-start ps-3">${item.label}</td>
                                <td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center">${item.s}</div></td>
                            </tr>
                        `;
                    } else if (item.type === 'final') {
                        html += `
                            <tr class="bg-warning bg-opacity-25 fw-bold text-dark">
                                <td class="text-start ps-3 fs-6">${item.label}</td>
                                <td colspan="2"><div contenteditable="true" class="print-editable w-100 text-center text-primary fs-5">${item.s}</div></td>
                            </tr>
                        `;
                    }
                });
                
                if (idx < tbodies.length - 1) {
                    html += `<tr><td colspan="3" style="border:none; height: 30px;"></td></tr>`;
                }
            });

            printTbody.innerHTML = html;
        } catch(error) {
            console.error("Terjadi error saat sinkronisasi NCV:", error);
        }
    }

    function savePrintData() {
        const selected = document.getElementById('cetak-modul-selector').value;
        if(!selected) {
            alert('Pilih modul cetak terlebih dahulu!');
            return;
        }

        if (selected === 'proximate') {
            const dataToSave = {
                modul: 'proximate',
                ref_no: document.getElementById('print-prox-ref-no').innerText,
                sample_id: document.getElementById('print-prox-sample-id').innerText,
                im_adb: document.getElementById('print-prox-im-adb').innerText,
                ash_adb: document.getElementById('print-prox-ash-adb').innerText,
                ash_db: document.getElementById('print-prox-ash-db').innerText,

            };
            
            console.log("Data siap dikirim ke backend:", dataToSave);
            alert("Hasil editan cetak berhasil ditangkap! Cek Console (F12) untuk melihat strukturnya.");
            
        }
    }
</script>
