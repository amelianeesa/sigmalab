<div id="print-module-gcv" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF GROSS CALORIFIC VALUE'])

    <table class="table table-bordered border-dark table-sm mt-3 print-full-table align-middle" style="font-size: 14px;">
        <tbody>
            <tr>
                <td class="fw-bold" style="width: 20%;">REFERENCE NO.</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-gcv-ref-no" class="print-editable w-100"></div></td>
                <td class="fw-bold" style="width: 20%;">CALORIMETER ID</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-gcv-cal-id" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">DATE</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-gcv-date" class="print-editable w-100"></div></td>
                <td class="fw-bold">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-gcv-std-method" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold">BALANCE ID</td>
                <td class="text-center">:</td>
                <td colspan="4"><div contenteditable="true" id="print-gcv-balance-id" class="print-editable w-100"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 14px;">
        <tbody id="print-gcv-dynamic-tbody">
            <!-- Di-generate otomatis oleh JS menyesuaikan pengujian -->
        </tbody>
    </table>
    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'gcv', 'kodeDokumen' => 'FOR/COAL-OPS/026', 'rev' => '01', 'tglBerlaku' => '21/02/2018'])
</div>