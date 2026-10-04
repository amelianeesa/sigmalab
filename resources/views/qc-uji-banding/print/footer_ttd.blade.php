<div class="row mt-5 print-full-table" style="font-size: 14px;">
    <div class="col-6">
        <div>Analyzed by:</div>
        <div contenteditable="true" id="print-{{ $idPrefix }}-analyzed-by" class="print-editable fw-bold mt-4 border-bottom border-dark" style="min-height: 1.5em;"></div>
        <div class="mt-1">Date: <span contenteditable="true" id="print-{{ $idPrefix }}-analyzed-date" class="print-editable"></span></div>
    </div>
    <div class="col-6">
        <div>Verified by:</div>
        <div contenteditable="true" id="print-{{ $idPrefix }}-verified-by" class="print-editable fw-bold mt-4 border-bottom border-dark" style="min-height: 1.5em;"></div>
        <div class="mt-1">Date: <span contenteditable="true" id="print-{{ $idPrefix }}-verified-date" class="print-editable"></span></div>
    </div>
</div>

<div style="margin-top: 80px;"> 
    <div class="border-top border-dark border-1 pt-2"> 
        <table class="table table-borderless table-sm mb-0 w-100" style="font-size: 11px;">
            <tr>
                <td class="p-0 text-start" style="width: 25%;">{{ $kodeDokumen ?? '-' }}</td>
                <td class="p-0 text-center" style="width: 25%;">Rev: {{ $rev ?? '-' }}</td>
                <td class="p-0 text-center" style="width: 25%;">Tgl. Berlaku: {{ $tglBerlaku ?? '-' }}</td>
                <td class="p-0 text-end" style="width: 25%;">Hal 1 dari 1 hal</td>
            </tr>
        </table>
    </div>
</div>