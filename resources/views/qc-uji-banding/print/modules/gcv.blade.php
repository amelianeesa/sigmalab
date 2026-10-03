<div id="print-module-gcv" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF GROSS CALORIFIC VALUE'])

    <table class="table table-borderless table-sm mt-3 print-full-table align-middle" style="font-size: 14px;">
        <tbody>
            <tr>
                <td class="text-uppercase fw-normal" style="width: 20%;">REFERENCE NO.</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-gcv-ref-no" class="print-editable w-100 fw-normal"></div></td>
                <td class="text-uppercase fw-normal" style="width: 20%;">CALORIMETER ID</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-gcv-cal-id" class="print-editable w-100 fw-normal"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase fw-normal">DATE</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-gcv-date" class="print-editable w-100 fw-normal"></div></td>
                <td class="text-uppercase fw-normal">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-gcv-std-method" class="print-editable w-100 fw-normal"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase fw-normal">BALANCE ID</td>
                <td class="text-center">:</td>
                <td colspan="4"><div contenteditable="true" id="print-gcv-balance-id" class="print-editable w-100 fw-normal"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 14px;">
        <tbody id="print-gcv-dynamic-tbody">
            
        </tbody>
    </table>
    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'gcv', 'kodeDokumen' => 'FOR/COAL-OPS/030', 'rev' => '04', 'tglBerlaku' => '02/07/2021'])
</div>