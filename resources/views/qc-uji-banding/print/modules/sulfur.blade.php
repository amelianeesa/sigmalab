<div id="print-module-sulfur" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF SULFUR BY IR SPECTROMETRY'])

    <table class="table table-bordered border-dark table-sm mt-3 print-full-table align-middle" style="font-size: 14px;">
        <tbody>
            <tr>
                <td class="fw-bold text-uppercase" style="width: 25%;">REFERENCE NO</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 73%;"><div contenteditable="true" id="print-sulfur-ref-no" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-sulfur-std-method" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">FURNACE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-sulfur-furnace-id" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">BALANCE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-sulfur-balance-id" class="print-editable w-100"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle" style="font-size: 14px;">
        <tbody id="print-sulfur-dynamic-tbody">
            <!-- Di-generate otomatis oleh JS -->
        </tbody>
    </table>
    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'sulfur', 'kodeDokumen' => 'FOR/COAL-OPS/XXX', 'rev' => '02', 'tglBerlaku' => '03/08/2021'])
</div>
