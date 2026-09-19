<div class="row g-2 mb-3 bg-light p-2 border">
    <div class="col-md-3"><label class="small fw-bold">Reference No</label><input type="text" name="params[{{ $pid_c }}][ref_no]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-3"><label class="small fw-bold">Balance ID</label><input type="text" name="params[{{ $pid_c }}][blnc_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-3"><label class="small fw-bold">Calorimeter ID</label><input type="text" name="params[{{ $pid_c }}][furnace_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-3"><label class="small fw-bold">Reference Method</label><input type="text" name="params[{{ $pid_c }}][std_method]" class="form-control form-control-sm param-input-ext" disabled></div>
</div>

<div class="table-responsive mb-3">
    <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid_c }}" data-pid="{{ $pid_c }}" data-code="CHN">
        <thead class="table-light">
            <tr>
                <th rowspan="2" class="align-middle">Parameter</th>
                <th rowspan="2" class="align-middle">Simplo</th>
                <th rowspan="2" class="align-middle">Duplo</th>
                <th colspan="2" class="align-middle border-bottom-0">Precision</th>
                <th rowspan="2" class="align-middle bg-warning bg-opacity-25">Average %</th>
            </tr>
            <tr>
                <th class="align-middle">Absolute Diff.</th>
                <th class="align-middle">YES/NO</th>
            </tr>
        </thead>
        <tbody>
            {{-- ROW 1: WEIGHT --}}
            <tr class="row-entry" data-type="weight">
                <td class="fw-bold">Weight (mg)</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-1" name="params[{{ $pid_c }}][data][0][mentah][w_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-2" name="params[{{ $pid_c }}][data][0][mentah][w_2]" disabled></td>
                <td class="align-middle out-diff fw-bold">-</td>
                <td class="align-middle">
                    <select class="form-select form-select-sm" name="params[{{ $pid_c }}][data][0][mentah][w_yesno]" disabled>
                        <option value=""></option><option value="YES">YES</option><option value="NO">NO</option>
                    </select>
                </td>
                <td class="align-middle bg-warning bg-opacity-25 fw-bold fs-6"><span class="out-avg-txt">-</span></td>
            </tr>
            {{-- ROW 2: CARBON --}}
            <tr class="row-entry" data-type="carbon">
                <td class="fw-bold">Carbon %db</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-c-1" name="params[{{ $pid_c }}][data][0][mentah][c_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-c-2" name="params[{{ $pid_c }}][data][0][mentah][c_2]" disabled></td>
                <td class="align-middle out-diff fw-bold">-</td>
                <td class="align-middle">
                    <select class="form-select form-select-sm" name="params[{{ $pid_c }}][data][0][yesno]" disabled>
                        <option value=""></option><option value="YES">YES</option><option value="NO">NO</option>
                    </select>
                </td>
                <td class="align-middle bg-warning bg-opacity-25 fw-bold fs-6"><span class="out-avg-txt">-</span>
                    <input type="hidden" class="in-hasil-1" name="params[{{ $pid_c }}][data][0][hasil_1]" disabled>
                    <input type="hidden" class="in-hasil-2" name="params[{{ $pid_c }}][data][0][hasil_2]" disabled>
                </td>
            </tr>
            {{-- ROW 3: HYDROGEN --}}
            <tr class="row-entry" data-type="hydrogen">
                <td class="fw-bold">Hydrogen %db</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-h-1" name="params[{{ $pid_h }}][data][0][mentah][h_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-h-2" name="params[{{ $pid_h }}][data][0][mentah][h_2]" disabled></td>
                <td class="align-middle out-diff fw-bold">-</td>
                <td class="align-middle">
                    <select class="form-select form-select-sm" name="params[{{ $pid_h }}][data][0][yesno]" disabled>
                        <option value=""></option><option value="YES">YES</option><option value="NO">NO</option>
                    </select>
                </td>
                <td class="align-middle bg-warning bg-opacity-25 fw-bold fs-6"><span class="out-avg-txt">-</span>
                    <input type="hidden" class="in-hasil-1" name="params[{{ $pid_h }}][data][0][hasil_1]" disabled>
                    <input type="hidden" class="in-hasil-2" name="params[{{ $pid_h }}][data][0][hasil_2]" disabled>
                </td>
            </tr>
            {{-- ROW 4: NITROGEN --}}
            <tr class="row-entry" data-type="nitrogen">
                <td class="fw-bold">Nitrogen %db</td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-n-1" name="params[{{ $pid_n }}][data][0][mentah][n_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-n-2" name="params[{{ $pid_n }}][data][0][mentah][n_2]" disabled></td>
                <td class="align-middle out-diff fw-bold">-</td>
                <td class="align-middle">
                    <select class="form-select form-select-sm" name="params[{{ $pid_n }}][data][0][yesno]" disabled>
                        <option value=""></option><option value="YES">YES</option><option value="NO">NO</option>
                    </select>
                </td>
                <td class="align-middle bg-warning bg-opacity-25 fw-bold fs-6"><span class="out-avg-txt">-</span>
                    <input type="hidden" class="in-hasil-1" name="params[{{ $pid_n }}][data][0][hasil_1]" disabled>
                    <input type="hidden" class="in-hasil-2" name="params[{{ $pid_n }}][data][0][hasil_2]" disabled>
                </td>
            </tr>
        </tbody>
    </table>
</div>
