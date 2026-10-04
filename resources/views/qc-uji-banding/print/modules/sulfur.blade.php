<div id="print-module-sulfur" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF SULFUR BY IR SPECTROMETRY'])

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
                <td><div contenteditable="true" id="print-sulfur-ref-no" class="print-editable w-100 fw-normal"></div></td>
                
                <td class="text-uppercase fw-normal ps-2">FURNACE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-sulfur-furnace-id" class="print-editable w-100 fw-normal"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase fw-normal ps-2">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-sulfur-std-method" class="print-editable w-100 fw-normal"></div></td>
                
                <td class="text-uppercase fw-normal ps-2">BALANCE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-sulfur-balance-id" class="print-editable w-100 fw-normal"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle" style="font-size: 14px;">
        <tbody id="print-sulfur-dynamic-tbody">
            
        </tbody>
    </table>
    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'sulfur', 'kodeDokumen' => 'FOR/COAL-OPS/026', 'rev' => '01', 'tglBerlaku' => '21/02/2018'])
</div>