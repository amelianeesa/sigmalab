<div class="row g-2 mb-3 bg-light p-2 border">
    <div class="col-md-3"><label class="small fw-bold">Reference No</label><input type="text" name="params[{{ $pid }}][ref_no]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-3"><label class="small fw-bold">Balance ID</label><input type="text" name="params[{{ $pid }}][blnc_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-3"><label class="small fw-bold">Calorimeter ID</label><input type="text" name="params[{{ $pid }}][calorimeter_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-3"><label class="small fw-bold">Reference Method</label><input type="text" name="params[{{ $pid }}][std_method]" class="form-control form-control-sm param-input-ext" disabled></div>
</div>

<div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="NCV">
        <thead class="table-light">
            <tr>
                <th style="min-width: 80px;">Pengujian Ke-</th>
                <th style="min-width: 130px;">Date</th>
                <th style="min-width: 80px;">BOMB NO</th>
                <th style="min-width: 100px;">Call ID</th>
                <th style="min-width: 130px;">Qv (ad) gross</th>
                <th style="min-width: 140px;" class="bg-warning bg-opacity-10">Avg. Qv (ad) gross</th>
                <th style="min-width: 120px;">Total Moisture</th>
                <th style="min-width: 150px;">Moisture in Analysis</th>
                <th style="min-width: 110px;">Hydrogen (ad)</th>
                <th style="min-width: 110px;">Nitrogen (ad)</th>
                <th style="min-width: 110px;">Oxygen (ad)</th>
                <th style="min-width: 150px;">R (Gas Constant at 25&deg;C)</th>
                <th style="min-width: 110px;">T (K) at 25&deg;C</th>
                <th style="min-width: 170px;">Hvap (constant pressure at 25&deg;C)</th>
                <th style="min-width: 100px;">Qv&rarr;p</th>
                <th style="min-width: 100px;">Qh</th>
                <th style="min-width: 100px;">Qm (ar)</th>
                <th style="min-width: 130px;">Qv (ad) gross (J/g)</th>
                <th style="min-width: 130px;" class="bg-warning bg-opacity-25">Qpar (net)</th>
            </tr>
        </thead>
        <tbody class="generic-tbody">
            <tr class="row-entry simplo-row">
                <td rowspan="2" class="align-middle">
                    <input type="number" class="form-control form-control-sm text-center mx-auto" name="params[{{ $pid }}][data][0][pengujian_ke]" style="width: 70px; min-width: 70px;" value="1">
                </td>
                <td rowspan="2" class="align-middle">
                    <input type="date" class="form-control form-control-sm" name="params[{{ $pid }}][data][0][tanggal_uji]" style="width: 130px; min-width: 130px;" value="{{ date('Y-m-d') }}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm in-bomb-1 bg-light border-0" readonly tabindex="-1" name="params[{{ $pid }}][data][0][dish_1]">
                    <input type="hidden" class="in-d1" name="params[{{ $pid }}][data][0][d1]">
                </td>
                <td><input type="text" class="form-control form-control-sm in-callid-1 bg-light border-0" readonly tabindex="-1"></td>
                <td><input type="text" class="form-control form-control-sm in-qvad-1 bg-light border-0" readonly tabindex="-1"></td>

                <td rowspan="2" class="align-middle bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-avgqv text-center bg-transparent border-0 fw-bold" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-tm text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-im text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-h text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-n text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-o text-center bg-light border-0" readonly tabindex="-1"></td>

                <td rowspan="2" class="align-middle"><input type="text" inputmode="decimal" class="form-control form-control-sm in-r text-center" name="params[{{ $pid }}][data][0][mentah][r]" disabled></td>
                <td rowspan="2" class="align-middle"><input type="text" inputmode="decimal" class="form-control form-control-sm in-t text-center" name="params[{{ $pid }}][data][0][mentah][t]" disabled></td>
                <td rowspan="2" class="align-middle"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hvap text-center" name="params[{{ $pid }}][data][0][mentah][hvap]" disabled></td>

                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-qvp text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-qh text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-qmar text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-qvadj text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle"><input type="text" class="form-control form-control-sm in-qparj text-center bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
            </tr>
            <tr class="row-entry duplo-row">
                <td>
                    <input type="text" class="form-control form-control-sm in-bomb-2 bg-light border-0" readonly tabindex="-1" name="params[{{ $pid }}][data][0][dish_2]">
                    <input type="hidden" class="in-d2" name="params[{{ $pid }}][data][0][d2]">
                </td>
                <td><input type="text" class="form-control form-control-sm in-callid-2 bg-light border-0" readonly tabindex="-1"></td>
                <td><input type="text" class="form-control form-control-sm in-qvad-2 bg-light border-0" readonly tabindex="-1"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="18" class="text-start">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Bomb No, Call ID, dan Qv (ad) gross otomatis diambil dari modul Determination of Gross Calorific Value (GCV).</small>
                </td>
            </tr>
        </tfoot>
    </table>
</div>