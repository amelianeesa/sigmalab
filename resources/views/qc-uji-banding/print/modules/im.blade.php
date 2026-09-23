<div id="print-module-im" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF MOISTURE IN THE ANALYSIS SAMPLE'])

    <table class="table table-bordered border-dark table-sm mt-3 print-full-table align-middle" style="font-size: 14px;">
        <tbody>
            <tr>
                <td class="fw-bold" style="width: 20%;">REFERENCE NO.</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-im-ref-no" class="print-editable w-100"></div></td>
                <td class="fw-bold" style="width: 20%;">BALANCE ID</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-im-balance-id" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold">OVEN ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-im-oven-id" class="print-editable w-100"></div></td>
                <td class="fw-bold">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-im-std-method" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">TIME START-FISNISH</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-im-time" class="print-editable w-100"></div></td>
                <td class="fw-bold text-uppercase">OVEN INDICATED TEMP</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-im-temp" class="print-editable w-100"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 14px;">
        <!-- Akan diisi otomatis oleh JS agar dinamis menyesuaikan jumlah pengujian -->
        <tbody id="print-im-dynamic-tbody">
        </tbody>
    </table>
</div>
