<div class="row g-2 mb-3 bg-light p-2 border">
    <div class="col-md-2"><label class="small fw-bold">Reference No</label><input type="text" name="params[{{ $pid }}][ref_no]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">BLNC ID</label><input type="text" name="params[{{ $pid }}][blnc_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Time</label><input type="text" name="params[{{ $pid }}][time]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Furnace ID</label><input type="text" name="params[{{ $pid }}][furnace_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Std Method</label><input type="text" name="params[{{ $pid }}][std_method]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Indicate T</label><input type="text" name="params[{{ $pid }}][indicate_t]" class="form-control form-control-sm param-input-ext" disabled></div>
</div>

<div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="GCV">
        <thead class="table-light">
            <tr>
                <th style="min-width: 80px;">Pengujian Ke-</th>
                <th style="min-width: 130px;">Date</th>
                <th style="min-width: 80px;">BOMB NO</th>
                <th style="min-width: 100px;">Call ID</th>
                <th style="min-width: 120px;">Weight of Crucible</th>
                <th style="min-width: 150px;">Weight of Crucible + Sample</th>
                <th style="min-width: 120px;">Sample Mass</th>
                <th style="min-width: 120px;">Preliminary Result</th>
                <th style="min-width: 100px;">Ee</th>
                <th style="min-width: 100px;">t</th>
                <th style="min-width: 120px;">Volume of Titrant</th>
                <th style="min-width: 120px;">Normality of Titrant</th>
                <th style="min-width: 100px;">e1</th>
                <th style="min-width: 120px;">Length of Fuse</th>
                <th style="min-width: 150px;">Heat of Comb. of Fuse</th>
                <th style="min-width: 100px;">e2</th>
                <th style="min-width: 120px;">Total Sulfur</th>
                <th style="min-width: 100px;">e3</th>
                <th style="min-width: 130px;">Aid Combustion Mass</th>
                <th style="min-width: 100px;">e4</th>
                <th style="min-width: 120px;" class="bg-warning bg-opacity-25">Final Result</th>
                <th colspan="2" style="min-width: 150px;">ABSOLUTE DIFFERENCE</th>
                <th style="min-width: 120px;">AVERAGE %</th>
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
                <td><input type="text" class="form-control form-control-sm in-bomb-1" name="params[{{ $pid }}][data][0][dish_1]" placeholder="S" disabled>
                    <input type="hidden" class="in-d1" name="params[{{ $pid }}][data][0][d1]">
                </td>
                <td><input type="text" class="form-control form-control-sm in-call-1" name="params[{{ $pid }}][data][0][mentah][call_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-wc-1" name="params[{{ $pid }}][data][0][mentah][wc_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-wcs-1 bg-light border-0" readonly tabindex="-1"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-1" name="params[{{ $pid }}][data][0][mentah][mass_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pre-1" name="params[{{ $pid }}][data][0][mentah][pre_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-1" name="params[{{ $pid }}][data][0][mentah][ee_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-t-1 bg-light border-0" readonly tabindex="-1"></td>
                
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-1" name="params[{{ $pid }}][data][0][mentah][vt_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-nt-1" name="params[{{ $pid }}][data][0][mentah][nt_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-e1-1 bg-light border-0" readonly tabindex="-1"></td>
                
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-1" name="params[{{ $pid }}][data][0][mentah][lf_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-hf-1" name="params[{{ $pid }}][data][0][mentah][hf_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-e2-1 bg-light border-0" readonly tabindex="-1"></td>
                
                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ts-1" name="params[{{ $pid }}][data][0][mentah][ts_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-e3-1 bg-light border-0" readonly tabindex="-1"></td>
                <td class="text-center text-muted">-</td>
                <td class="text-center text-muted">-</td>
                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                
                <td rowspan="2" class="align-middle out-diff">-</td>
                <td rowspan="2" class="align-middle p-1">
                    <select class="form-select form-select-sm fw-bold" name="params[{{ $pid }}][data][0][yesno]" onchange="updateYesNoColor(this)">
                        <option value=""></option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </td>
                <td rowspan="2" class="align-middle fw-bold out-avg-adb">-</td>
            </tr>
            <tr class="row-entry duplo-row">
                <td><input type="text" class="form-control form-control-sm in-bomb-2" name="params[{{ $pid }}][data][0][dish_2]" placeholder="D" disabled>
                    <input type="hidden" class="in-d2" name="params[{{ $pid }}][data][0][d2]">
                </td>
                <td><input type="text" class="form-control form-control-sm in-call-2" name="params[{{ $pid }}][data][0][mentah][call_2]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-wc-2" name="params[{{ $pid }}][data][0][mentah][wc_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-wcs-2 bg-light border-0" readonly tabindex="-1"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-2" name="params[{{ $pid }}][data][0][mentah][mass_2]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pre-2" name="params[{{ $pid }}][data][0][mentah][pre_2]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-2" name="params[{{ $pid }}][data][0][mentah][ee_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-t-2 bg-light border-0" readonly tabindex="-1"></td>
                
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-2" name="params[{{ $pid }}][data][0][mentah][vt_2]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-nt-2" name="params[{{ $pid }}][data][0][mentah][nt_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-e1-2 bg-light border-0" readonly tabindex="-1"></td>
                
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-2" name="params[{{ $pid }}][data][0][mentah][lf_2]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-hf-2" name="params[{{ $pid }}][data][0][mentah][hf_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-e2-2 bg-light border-0" readonly tabindex="-1"></td>
                
                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ts-2" name="params[{{ $pid }}][data][0][mentah][ts_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-e3-2 bg-light border-0" readonly tabindex="-1"></td>
                <td class="text-center text-muted">-</td>
                <td class="text-center text-muted">-</td>
                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="22" class="text-start">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-row mt-2" data-pid="{{ $pid }}" data-code="GCV" disabled>
                        <i class="fas fa-plus"></i> Tambah Pengujian
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-last-row mt-2" data-pid="{{ $pid }}" disabled>
                        <i class="fas fa-minus"></i> Hapus Pengujian Terakhir
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
