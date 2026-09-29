<div class="row g-2 mb-3 bg-light p-2 border">
    <div class="col-md-2"><label class="small fw-bold">Reference No</label><input type="text" name="params[{{ $pid }}][ref_no]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">BLNC ID</label><input type="text" name="params[{{ $pid }}][blnc_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Time</label><input type="text" name="params[{{ $pid }}][time]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Furnace ID</label><input type="text" name="params[{{ $pid }}][furnace_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Std Method</label><input type="text" name="params[{{ $pid }}][std_method]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-md-2"><label class="small fw-bold">Indicate T</label><input type="text" name="params[{{ $pid }}][indicate_t]" class="form-control form-control-sm param-input-ext" disabled></div>
</div>
<div class="table-responsive mb-3" style="overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
        <thead class="table-light">
            @if($code === 'IM' || $code === 'RM')
                <tr>
                    <th>Pengujian Ke-</th>
                    <th>Date</th>
                    <th>DISH NO</th>
                    <th>M1</th>
                    <th>M2</th>
                    <th>M3</th>
                    <th>A</th>
                    <th>B</th>
                    <th class="bg-warning bg-opacity-25">M%</th>
                    <th colspan="2">ABSOLUTE DIFFERENCE</th>
                    <th>AVERAGE %</th>
                    </tr>
            @elseif($code === 'ASH')
                <tr>
                    <th>Pengujian Ke-</th>
                    <th>Date</th>
                    <th>DISH NO</th>
                    <th>M1</th>
                    <th>M2</th>
                    <th>M2-M1</th>
                    <th>M3</th>
                    <th>M3-M1</th>
                    <th class="bg-warning bg-opacity-25">ASH%</th>
                    <th colspan="2">ABSOLUTE DIFFERENCE</th>
                    
                    <th>AVERAGE %adb</th>
                    </tr>
            @elseif($code === 'VM')
                <tr>
                    <th>Pengujian Ke-</th>
                    <th>Date</th>
                    <th>DISH NO</th>
                    <th>M1</th>
                    <th>M2</th>
                    <th>M2-M1</th>
                    <th>M3</th>
                    <th>M2-M3</th>
                    <th>%LOSS</th>
                    <th>%M adb</th>
                    <th class="bg-warning bg-opacity-25">%VM</th>
                    <th colspan="2">ABSOLUTE DIFFERENCE</th>
                    
                    <th>AVERAGE %adb</th>
                    </tr>
            @endif
        </thead>
        <tbody class="proximate-tbody" data-index="0">
            <!-- SIMPLO -->
            <tr class="row-entry simplo-row">
                <td rowspan="2" class="align-middle">
                    <input type="number" class="form-control form-control-sm text-center mx-auto" name="params[{{ $pid }}][data][0][pengujian_ke]" style="width: 70px; min-width: 70px;" value="1">
                </td>
                <td rowspan="2" class="align-middle">
                    <input type="date" class="form-control form-control-sm" name="params[{{ $pid }}][data][0][tanggal_uji]" style="width: 130px; min-width: 130px;" value="{{ date('Y-m-d') }}">
                </td>
                <!-- Kolom Date Dihapus, hidden input pindah ke sini -->
                <td>
                    <input type="text" class="form-control form-control-sm in-dish-1" name="params[{{ $pid }}][data][0][dish_1]" placeholder="S" disabled>
                    <input type="hidden" class="in-d1" name="params[{{ $pid }}][data][0][d1]">
                    <input type="hidden" class="in-db-1" name="params[{{ $pid }}][data][0][db1]">
                </td>
                
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][data][0][mentah][m1_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                
                @if($code === 'ASH' || $code === 'VM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][data][0][mentah][m2m1_1]" disabled></td>
                @endif
                
                <!-- Ini M3 Simplo (Pakai angka 1) -->
                <td><input type="text" class="form-control form-control-sm in-m3-1 bg-light border-0" name="params[{{ $pid }}][data][0][mentah][m3_1]" readonly tabindex="-1"></td>
                
                @if($code === 'IM' || $code === 'RM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-1" name="params[{{ $pid }}][data][0][mentah][a_1]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-b-1" name="params[{{ $pid }}][data][0][mentah][b_1]" disabled></td>
                @elseif($code === 'ASH')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3m1-1" name="params[{{ $pid }}][data][0][mentah][m3m1_1]" disabled></td>
                @elseif($code === 'VM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m3-1" name="params[{{ $pid }}][data][0][mentah][m2m3_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-loss-1 bg-light border-0" readonly tabindex="-1"></td>
                <td rowspan="2" class="align-middle bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-im-1 fw-bold bg-transparent border-0 text-center text-warning" readonly tabindex="-1"></td>
                @endif

                @if($code === 'VM' || $code === 'TOTAL SULFUR (%AD/DB)')
                <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold text-center text-primary" name="params[{{ $pid }}][data][0][mentah][vm_manual_1]"></td>
                @else
                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                @endif

                <td rowspan="2" class="align-middle out-diff">-</td>
                <td rowspan="2" class="align-middle">
                    <select class="form-select form-select-sm fw-bold" name="params[{{ $pid }}][data][0][yesno]" onchange="updateYesNoColor(this)">
                        <option value=""></option>
                        <option value="YES">YES</option>
                        <option value="NO">NO</option>
                    </select>
                </td>
                <td rowspan="2" class="align-middle out-avg-adb">-</td>
                
            </tr>
            <!-- DUPLO -->
            <tr class="row-entry duplo-row">
                <td><input type="text" class="form-control form-control-sm in-dish-2" name="params[{{ $pid }}][data][0][dish_2]" placeholder="D" disabled>
                    <input type="hidden" class="in-d2" name="params[{{ $pid }}][data][0][d2]">
                    <input type="hidden" class="in-db-2" name="params[{{ $pid }}][data][0][db2]">
                </td>
                
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][data][0][mentah][m1_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                
                @if($code === 'ASH' || $code === 'VM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="params[{{ $pid }}][data][0][mentah][m2m1_2]" disabled></td>
                @endif
                
                <!-- Ini M3 Duplo (Pakai angka 2) -->
                <td><input type="text" class="form-control form-control-sm in-m3-2 bg-light border-0" name="params[{{ $pid }}][data][0][mentah][m3_2]" readonly tabindex="-1"></td>
                
                @if($code === 'IM' || $code === 'RM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-2" name="params[{{ $pid }}][data][0][mentah][a_2]" disabled></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-b-2" name="params[{{ $pid }}][data][0][mentah][b_2]" disabled></td>
                @elseif($code === 'ASH')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3m1-2" name="params[{{ $pid }}][data][0][mentah][m3m1_2]" disabled></td>
                @elseif($code === 'VM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m3-2" name="params[{{ $pid }}][data][0][mentah][m2m3_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-loss-2 bg-light border-0" readonly tabindex="-1"></td>
                
                @endif

                @if($code === 'VM' || $code === 'TOTAL SULFUR (%AD/DB)')
<td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold text-center text-primary" name="params[{{ $pid }}][data][0][mentah][vm_manual_2]"></td>
@else
<td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
@endif
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="15" class="text-start">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-row mt-2 me-2" data-pid="{{ $pid }}" data-code="{{ $code }}" disabled>
                        <i class="fas fa-plus"></i> Tambah Pengujian
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-last-row mt-2" disabled>
                        <i class="fas fa-minus"></i> Hapus Pengujian Terakhir
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>