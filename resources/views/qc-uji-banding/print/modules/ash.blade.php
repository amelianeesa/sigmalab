<div id="print-module-ash" class="print-module-container" style="display: none;">
    @include('qc-uji-banding.print.kop_surat', ['title' => 'DETERMINATION OF ASH CONTENT'])

    <!-- TABEL 1: INFORMASI META -->
    <table class="table table-bordered border-dark table-sm mt-3 print-full-table align-middle" style="font-size: 14px;">
        <tbody>
            <tr>
                <td class="fw-bold text-uppercase" style="width: 20%;">REFERENCE NO.</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-ash-ref-no" class="print-editable w-100"></div></td>
                <td class="fw-bold text-uppercase" style="width: 20%;">FURNACE ID</td>
                <td style="width: 2%; text-align: center;">:</td>
                <td style="width: 28%;"><div contenteditable="true" id="print-ash-furnace-id" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">BALANCE ID</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-ash-balance-id" class="print-editable w-100"></div></td>
                <td class="fw-bold text-uppercase">STANDARD METHOD</td>
                <td class="text-center">:</td>
                <td><div contenteditable="true" id="print-ash-std-method" class="print-editable w-100"></div></td>
            </tr>
            <tr>
                <td class="fw-bold text-uppercase">FURNACE INDICATED TEMP</td>
                <td class="text-center">:</td>
                <td colspan="4"><div contenteditable="true" id="print-ash-indicate-t" class="print-editable w-100"></div></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered border-dark table-sm mt-4 print-full-table align-middle text-center" style="font-size: 14px; table-layout: fixed;">
        <tbody id="print-ash-dynamic-tbody">
        </tbody>
    </table>

    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'ash', 'kodeDokumen' => 'FOR/COAL-OPS/023', 'rev' => '01', 'tglBerlaku' => '21/02/2018'])
</div>