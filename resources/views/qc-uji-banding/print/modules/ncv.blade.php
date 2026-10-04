<div id="print-module-ncv" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF NET CALORIFIC VALUE'])

    <table class="table table-borderless table-sm mt-3 print-full-table align-middle" style="font-size: 14px; table-layout: fixed;">
        <colgroup>
            <col style="width: 20%;">
            <col style="width: 2%;">
            <col style="width: 28%;">
            <col style="width: 20%;">
            <col style="width: 2%;">
            <col style="width: 28%;">
        </colgroup>
        <tbody>
            <tr>
                <td class="text-uppercase ps-2">REFERENCE NO</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-ncv-ref-no" class="print-editable w-100"></div></td>
                <td class="text-uppercase ps-2">CALORIMETER ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-ncv-cal-id" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase ps-2">DATE</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-ncv-date" class="print-editable w-100"></div></td>
                <td class="text-uppercase ps-2">REFERENCE METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-ncv-std-method" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase ps-2">BALANCE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-ncv-balance-id" class="print-editable w-100"></div></td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 13px;">
        <colgroup>
            <col style="width: 40%;">
            <col style="width: 30%;">
            <col style="width: 30%;">
        </colgroup>
        <tbody id="print-ncv-dynamic-tbody">
            
        </tbody>
    </table>

    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'ncv', 'kodeDokumen' => 'FOR/COAL-OPS/XXX', 'rev' => '01', 'tglBerlaku' => '21/02/2018'])
</div>