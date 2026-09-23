<div id="print-module-proximate" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'PROXIMATE ANALYSIS (ASTM)'])

    <table class="table table-bordered border-dark table-sm mt-3 print-full-table align-middle" style="font-size: 14px;">
        <tbody>
            <tr>
                <td class="fw-bold text-uppercase" style="width: 20%;">REFERENCE NO.</td>
                <td style="width: 5%; text-align: center;">:</td>
                <td style="width: 25%;"><div contenteditable="true" id="print-prox-ref-no" class="print-editable w-100"></div></td>
                <td class="fw-bold text-uppercase" style="width: 20%;">STANDARD METHOD</td>
                <td colspan="3" style="width: 30%;">: <span contenteditable="true" id="print-prox-std-method" class="print-editable"></span></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">SAMPLE ID</td>
                <td colspan="2"><div contenteditable="true" id="print-prox-sample-id" class="print-editable w-100 text-center fw-bold"></div></td>
                <td></td>
                <td style="width: 10%;"></td>
                <td style="width: 10%;"></td>
                <td style="width: 10%;"></td>
            </tr>
            <tr class="text-center">
                <td rowspan="2" class="fw-bold text-uppercase align-middle">PARAMETER</td>
                <td colspan="2" class="fw-bold">Moisture Basis</td>
                <td colspan="2" class="fw-bold">Moisture Basis</td>
                <td colspan="2" class="fw-bold">Moisture Basis</td>
            </tr>
            <tr class="text-center">
                <td class="fw-bold" style="width: 10%;">%adb</td>
                <td class="fw-bold" style="width: 10%;">%db</td>
                <td class="fw-bold" style="width: 10%;">%adb</td>
                <td class="fw-bold" style="width: 10%;">%db</td>
                <td class="fw-bold" style="width: 10%;">%adb</td>
                <td class="fw-bold" style="width: 10%;">%db</td>
            </tr>
            <tr>
                <td>Moisture in analysis sample (M)</td>
                <td class="text-center"><div contenteditable="true" id="print-prox-im-adb" class="print-editable w-100"></div></td>
                <td style="background-color: #808080 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;"></td>
                <td></td>
                <td style="background-color: #808080 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;"></td>
                <td></td>
                <td style="background-color: #808080 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;"></td>
            </tr>
            <tr>
                <td>Ash content (A)</td>
                <td class="text-center"><div contenteditable="true" id="print-prox-ash-adb" class="print-editable w-100"></div></td>
                <td class="text-center"><div contenteditable="true" id="print-prox-ash-db" class="print-editable w-100"></div></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Volatile matter (VM)</td>
                <td class="text-center"><div contenteditable="true" id="print-prox-vm-adb" class="print-editable w-100"></div></td>
                <td class="text-center"><div contenteditable="true" id="print-prox-vm-db" class="print-editable w-100"></div></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Fixed Carbon (FC)</td>
                <td class="text-center"><div contenteditable="true" id="print-prox-fc-adb" class="print-editable w-100"></div></td>
                <td class="text-center"><div contenteditable="true" id="print-prox-fc-db" class="print-editable w-100"></div></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
