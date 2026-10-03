<div class="row g-3 mb-3">
    <div class="col-md-3">
        <label class="form-label small fw-bold">Reference No.</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="reference_no" name="params[{{ $pid }}][ref_no]" placeholder="e.g. ASTM D3302" disabled>
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-bold">BLNC ID</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="blnc_id" name="params[{{ $pid }}][blnc_id]" placeholder="..." disabled>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-bold">Time</label>
        <input type="time" class="form-control form-control-sm tm-meta" data-meta="time" name="params[{{ $pid }}][time]" disabled>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-bold">OVEN ID</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="oven_id" name="params[{{ $pid }}][furnace_id]" placeholder="..." disabled>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-bold">Std Method</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="std_method" name="params[{{ $pid }}][std_method]" placeholder="..." disabled>
    </div>
</div>

<div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-sm align-middle text-center input-table" id="table-{{ $pid }}" data-code="{{ $code }}" data-pid="{{ $pid }}">
        <thead class="table-light">
            <tr>
                <th class="align-middle">Pengujian Ke-</th>
                <th class="align-middle">Date</th>
                <th class="align-middle">Test</th>
                <th class="align-middle">%ADL 1<br><small>(Manual)</small></th>
                <th class="align-middle">%ADL 2<br><small>(Auto from Tab 2)</small></th>
                <th class="align-middle">%RM<br><small>(Auto from RM Tab 1)</small></th>
                <th class="align-middle">%TM'<br><small>(Auto)</small></th>
                <th class="align-middle bg-info bg-opacity-10">%TM</th>
                <th colspan="2" class="align-middle">ABSOLUTE DIFFERENCE</th>
                <th class="align-middle bg-warning bg-opacity-25">Average %<br>(As Received)</th>
            </tr>
        </thead>
        <tbody>
            
            <tr class="row-entry" data-type="simplo">
                <td rowspan="2" class="align-middle">
                    <input type="number" class="form-control form-control-sm text-center mx-auto" name="params[{{ $pid }}][data][0][pengujian_ke]" style="width: 70px; min-width: 70px;" value="1">
                </td>
                <td rowspan="2" class="align-middle">
                    <input type="date" class="form-control form-control-sm" name="params[{{ $pid }}][data][0][tanggal_uji]" style="width: 130px; min-width: 130px;" value="{{ date('Y-m-d') }}">
                </td>
                <td class="fw-bold">Simplo</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl1" name="params[{{ $pid }}][data][0][mentah][adl1_1]" placeholder="-" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl2 bg-light border-0" name="params[{{ $pid }}][data][0][mentah][adl2_1]" readonly tabindex="-1" placeholder="-" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-rm bg-light border-0" name="params[{{ $pid }}][data][0][mentah][rm_1]" readonly tabindex="-1" placeholder="-" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-tm-prime bg-light border-0" name="params[{{ $pid }}][data][0][mentah][tm_prime_1]" readonly tabindex="-1" placeholder="-" disabled></td>
                <td class="out-tm bg-info bg-opacity-10 fw-bold">-</td>
<input type="hidden" class="in-d1" name="params[{{ $pid }}][data][0][d1]" disabled>

                <td class="out-abs bg-light" rowspan="2">-</td>
                <td class="align-middle p-1" rowspan="2">
                    <select class="form-select form-select-sm fw-bold" name="params[{{ $pid }}][data][0][yesno]" onchange="updateYesNoColor(this)">
                        <option value=""></option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </td>
                <td class="out-avg-ar bg-warning bg-opacity-25 fw-bold fs-6" rowspan="2">-</td>
            </tr>
            
            <tr class="row-entry" data-type="duplo">
                <td class="fw-bold">Duplo</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl1" name="params[{{ $pid }}][data][0][mentah][adl1_2]" placeholder="-" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl2 bg-light border-0" name="params[{{ $pid }}][data][0][mentah][adl2_2]" readonly tabindex="-1" placeholder="-" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-rm bg-light border-0" name="params[{{ $pid }}][data][0][mentah][rm_2]" readonly tabindex="-1" placeholder="-" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-tm-prime bg-light border-0" name="params[{{ $pid }}][data][0][mentah][tm_prime_2]" readonly tabindex="-1" placeholder="-" disabled></td>
                <td class="out-tm bg-info bg-opacity-10 fw-bold">-</td>
            </tr>
        </tbody>
    </table>
</div>
