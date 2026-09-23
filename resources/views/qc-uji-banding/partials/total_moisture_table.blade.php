<div class="row g-3 mb-3">
    <div class="col-md-3">
        <label class="form-label small fw-bold">Reference No.</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="reference_no" placeholder="e.g. ASTM D3302">
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-bold">BLNC ID</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="blnc_id" placeholder="...">
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-bold">Time</label>
        <input type="time" class="form-control form-control-sm tm-meta" data-meta="time">
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-bold">OVEN ID</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="oven_id" placeholder="...">
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-bold">Std Method</label>
        <input type="text" class="form-control form-control-sm tm-meta" data-meta="std_method" placeholder="...">
    </div>
</div>

<div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-sm align-middle text-center input-table" data-code="{{ $code }}" data-pid="{{ $param->parameter_uji_id }}">
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
            {{-- SIMPLO --}}
            <tr class="row-entry" data-type="simplo">
                <td rowspan="2" class="align-middle">
                    <input type="number" class="form-control form-control-sm text-center mx-auto" name="params[{{ $pid }}][data][0][pengujian_ke]" style="width: 70px; min-width: 70px;" value="1">
                </td>
                <td rowspan="2" class="align-middle">
                    <input type="date" class="form-control form-control-sm" name="params[{{ $pid }}][data][0][tanggal_uji]" style="width: 130px; min-width: 130px;" value="{{ date('Y-m-d') }}">
                </td>
                <td class="fw-bold">Simplo</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl1" placeholder="-"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl2 bg-light border-0" readonly tabindex="-1" placeholder="-"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-rm bg-light border-0" readonly tabindex="-1" placeholder="-"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-tm-prime bg-light border-0" readonly tabindex="-1" placeholder="-"></td>
                <td class="out-tm bg-info bg-opacity-10 fw-bold">-</td>
                
                {{-- Absolute Difference & Average (Merge 2 baris) --}}
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
            {{-- DUPLO --}}
            <tr class="row-entry" data-type="duplo">
                <td class="fw-bold">Duplo</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl1" placeholder="-"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-adl2 bg-light border-0" readonly tabindex="-1" placeholder="-"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-rm bg-light border-0" readonly tabindex="-1" placeholder="-"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-tm-prime bg-light border-0" readonly tabindex="-1" placeholder="-"></td>
                <td class="out-tm bg-info bg-opacity-10 fw-bold">-</td>
            </tr>
        </tbody>
    </table>
</div>
