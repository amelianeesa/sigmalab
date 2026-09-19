<div class="row g-2 mb-3 bg-light p-2 border">
    <div class="col-md-2"><label class="small fw-bold">Reference No</label><input type="text" name="params[{{ $pid }}][ref_no]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">BLNC ID</label><input type="text" name="params[{{ $pid }}][blnc_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Time</label><input type="text" name="params[{{ $pid }}][time]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Furnace ID</label><input type="text" name="params[{{ $pid }}][furnace_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Std Method</label><input type="text" name="params[{{ $pid }}][std_method]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Indicate T</label><input type="text" name="params[{{ $pid }}][indicate_t]" class="form-control form-control-sm param-input-ext" disabled></div>
</div>
﻿<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
        <thead class="table-light">
            <tr>
                <th>DISH NO</th>
                <th>Mass of Sample</th>
                <th class="bg-warning bg-opacity-25">Total Sulfur %ad</th>
                <th colspan="2">ABSOLUTE DIFFERENCE</th>
                <th>AVERAGE %</th>
            </tr>
        </thead>
        <tbody class="generic-tbody">
            <tr class="row-entry simplo-row">
                <td><input type="text" class="form-control form-control-sm in-dish-1" name="params[{{ $pid }}][data][0][dish_1]" placeholder="S" disabled>
                    <input type="hidden" class="in-d1" name="params[{{ $pid }}][data][0][d1]">
                </td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-1" name="params[{{ $pid }}][data][0][mentah][mass_1]" disabled></td>
                <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="params[{{ $pid }}][data][0][mentah][ts_1]" disabled></td>
                
                <td rowspan="2" class="align-middle out-diff">-</td>
                <td rowspan="2" class="align-middle">
                    <select class="form-select form-select-sm" name="params[{{ $pid }}][data][0][yesno]" disabled>
                        <option value=""></option><option value="YES">YES</option><option value="NO">NO</option>
                    </select>
                </td>
                <td rowspan="2" class="align-middle fw-bold out-avg-adb">-</td>
            </tr>
            <tr class="row-entry duplo-row">
                <td><input type="text" class="form-control form-control-sm in-dish-2" name="params[{{ $pid }}][data][0][dish_2]" placeholder="D" disabled>
                    <input type="hidden" class="in-d2" name="params[{{ $pid }}][data][0][d2]">
                </td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-2" name="params[{{ $pid }}][data][0][mentah][mass_2]" disabled></td>
                <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="params[{{ $pid }}][data][0][mentah][ts_2]" disabled></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-start">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-row mt-2" data-pid="{{ $pid }}" data-code="{{ $code }}" disabled>
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
