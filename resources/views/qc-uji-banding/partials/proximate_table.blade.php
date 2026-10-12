@once
<style>
    .qc-meta-row label { font-size: 0.72rem; margin-bottom: 0.15rem; }

    .prox-hint {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.35rem;
    }

    .prox-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
    }

    .param-form-wrapper table.prox-table {
        width: auto;
        min-width: 0 !important;
        margin: 0;
    }

    .param-form-wrapper table.prox-table thead th {
        font-size: 0.7rem;
        padding: 0.3rem 0.35rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .param-form-wrapper table.prox-table td {
        padding: 0.22rem 0.28rem;
        vertical-align: middle;
        font-size: 0.75rem;
    }

    .param-form-wrapper .prox-table input.form-control-sm {
        width: 74px;
        min-width: 0 !important;
        padding: 0.2rem 0.3rem;
        font-size: 0.75rem !important;
        text-align: center;
    }

    .param-form-wrapper .prox-table input.prox-no { width: 50px; }
    .param-form-wrapper .prox-table input.prox-date { width: 112px; padding-left: 0.25rem; padding-right: 0.15rem; }
    .param-form-wrapper .prox-table input.prox-dish { width: 58px; }

    .param-form-wrapper .prox-table select.form-select-sm {
        width: 64px;
        min-width: 0 !important;
        padding: 0.2rem 1.4rem 0.2rem 0.4rem;
        font-size: 0.75rem !important;
    }

    .param-form-wrapper table.prox-table td.out-diff,
    .param-form-wrapper table.prox-table td.out-avg-adb {
        min-width: 56px !important;
        padding-left: 0.3rem !important;
        padding-right: 0.3rem !important;
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .prox-hint { display: block; }
        .param-form-wrapper table.prox-table thead th { font-size: 0.65rem; padding: 0.25rem 0.28rem; }
        .param-form-wrapper table.prox-table td { padding: 0.18rem 0.22rem; font-size: 0.7rem; }
        .param-form-wrapper .prox-table input.form-control-sm { width: 66px; font-size: 0.7rem !important; }
        .param-form-wrapper .prox-table input.prox-no { width: 44px; }
        .param-form-wrapper .prox-table input.prox-date { width: 104px; }
        .param-form-wrapper .prox-table input.prox-dish { width: 52px; }
        .param-form-wrapper .prox-table select.form-select-sm { width: 60px; }
    }
</style>
@endonce

<div class="row g-2 mb-2 bg-light p-2 border rounded qc-meta-row">
    <div class="col-6 col-md-2"><label class="fw-bold">Reference No</label><input type="text" name="params[{{ $pid }}][ref_no]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-6 col-md-2"><label class="fw-bold">BLNC ID</label><input type="text" name="params[{{ $pid }}][blnc_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-6 col-md-2"><label class="fw-bold">Time</label><input type="text" name="params[{{ $pid }}][time]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-6 col-md-2"><label class="fw-bold">Furnace ID</label><input type="text" name="params[{{ $pid }}][furnace_id]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-6 col-md-2"><label class="fw-bold">Std Method</label><input type="text" name="params[{{ $pid }}][std_method]" class="form-control form-control-sm param-input-ext" disabled></div>
    <div class="col-6 col-md-2"><label class="fw-bold">Indicate T</label><input type="text" name="params[{{ $pid }}][indicate_t]" class="form-control form-control-sm param-input-ext" disabled></div>
</div>

<div class="prox-hint"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>

<div class="table-responsive prox-scroll mb-2">
    <table class="table table-bordered table-sm align-middle text-center mb-0 param-table prox-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
        <thead>
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

            <tr class="row-entry simplo-row">
                <td rowspan="2" class="align-middle">
                    <input type="number" class="form-control form-control-sm text-center mx-auto prox-no" name="params[{{ $pid }}][data][0][pengujian_ke]" value="1">
                </td>
                <td rowspan="2" class="align-middle">
                    <input type="date" class="form-control form-control-sm prox-date" name="params[{{ $pid }}][data][0][tanggal_uji]" value="{{ date('Y-m-d') }}">
                </td>

                <td>
                    <input type="text" class="form-control form-control-sm in-dish-1 prox-dish" name="params[{{ $pid }}][data][0][dish_1]" placeholder="S" disabled>
                    <input type="hidden" class="in-d1" name="params[{{ $pid }}][data][0][d1]">
                    <input type="hidden" class="in-db-1" name="params[{{ $pid }}][data][0][db1]">
                </td>

                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][data][0][mentah][m1_1]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>

                @if($code === 'ASH' || $code === 'VM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][data][0][mentah][m2m1_1]" disabled></td>
                @endif

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

            <tr class="row-entry duplo-row">
                <td>
                    <input type="text" class="form-control form-control-sm in-dish-2 prox-dish" name="params[{{ $pid }}][data][0][dish_2]" placeholder="D" disabled>
                    <input type="hidden" class="in-d2" name="params[{{ $pid }}][data][0][d2]">
                    <input type="hidden" class="in-db-2" name="params[{{ $pid }}][data][0][db2]">
                </td>

                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][data][0][mentah][m1_2]" disabled></td>
                <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>

                @if($code === 'ASH' || $code === 'VM')
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="params[{{ $pid }}][data][0][mentah][m2m1_2]" disabled></td>
                @endif

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
    </table>
</div>