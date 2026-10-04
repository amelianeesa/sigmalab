<div id="print-module-proximate" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'PROXIMATE ANALYSIS (ASTM)'])

    <table class="table table-borderless table-sm mt-3 print-full-table align-middle" style="font-size: 13px; table-layout: fixed;">
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
                <td class="text-uppercase ps-2">REFERENCE NO.</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-prox-ref-no" class="print-editable w-100 fw-normal"></div></td>
                <td class="text-uppercase ps-2">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-prox-std-method" class="print-editable w-100 fw-normal"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 13px; table-layout: fixed;">
        <colgroup id="print-prox-colgroup">
            
        </colgroup>
        <thead>
            <tr id="print-prox-header-0">
                
            </tr>
    
            <tr class="text-center" id="print-prox-header-1">
                
            </tr>
        
            <tr class="text-center" id="print-prox-header-2">
                
            </tr>
        </thead>
        <tbody>
            <tr id="print-prox-row-im"></tr>
            <tr id="print-prox-row-ash"></tr>
            <tr id="print-prox-row-vm"></tr>
            <tr id="print-prox-row-fc"></tr>
        </tbody>
    </table>
    
    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'prox', 'kodeDokumen' => 'FOR/COAL-OPS/119', 'rev' => '02', 'tglBerlaku' => '03/08/2021'])
</div>