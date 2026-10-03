<div id="print-module-vm" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF VOLATILE MATTER'])

    <div id="print-vm-meta-date" style="display: none;"></div>

    <table class="table table-borderless table-sm mt-3 print-full-table align-middle" style="font-size: 14px;">
        <tbody>
            <tr>
                <td class="text-uppercase fw-normal" style="width: 20%;">REFERENCE NO.</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-vm-ref-no" class="print-editable w-100 fw-normal"></div></td>
                
                <td class="text-uppercase fw-normal" style="width: 20%;">FURNACE ID</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-vm-furnace-id" class="print-editable w-100 fw-normal"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase fw-normal">BALANCE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-vm-balance-id" class="print-editable w-100 fw-normal"></div></td>
                
                <td class="text-uppercase fw-normal">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-vm-std-method" class="print-editable w-100 fw-normal"></div></td>
            </tr>
            <tr>
                <td class="text-uppercase fw-normal">FURNACE INDICATED TEMP</td>
                <td class="text-center">:</td>
                <td colspan="4"><div contenteditable="true" id="print-vm-temp" class="print-editable w-100 fw-normal"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 14px;">
        <tbody id="print-vm-dynamic-tbody">
            
        </tbody>
    </table>
    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'vm', 'kodeDokumen' => 'FOR/COAL-OPS/026', 'rev' => '01', 'tglBerlaku' => '21/02/2018'])
</div>