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

<table class="table table-borderless table-sm mt-4 mb-0" style="font-size: 11px;">
    <tr>
        <td style="width: 30%;">{{ $kodeDokumen ?? '-' }}</td>
        <td style="width: 20%; text-align: center;">Rev: {{ $rev ?? '-' }}</td>
        <td style="width: 30%; text-align: center;">Tgl. Berlaku: {{ $tglBerlaku ?? '-' }}</td>
        <td style="width: 20%; text-align: right;">Hal 1 dari 1 hal</td>
    </tr>
</table>