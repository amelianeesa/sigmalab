<div id="print-module-tm" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF TOTAL MOISTURE'])

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
                <td class="text-uppercase fw-normal ps-2">REFERENCE NO</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-tm-ref-no" class="print-editable w-100 fw-normal"></div></td>
                
                <td class="text-uppercase fw-normal ps-2">BALANCE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-tm-balance-id" class="print-editable w-100 fw-normal"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase fw-normal ps-2">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-tm-std-method" class="print-editable w-100 fw-normal"></div></td>
                
                <td class="text-uppercase fw-normal ps-2">OVEN ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-tm-oven-id" class="print-editable w-100 fw-normal"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 13px;">
        <tbody id="print-tm-dynamic-tbody">
            
        </tbody>
    </table>

    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'tm', 'kodeDokumen' => 'FOR/COAL-OPS/024', 'rev' => '04', 'tglBerlaku' => '16/08/2018'])
</div>