<div class="p-4">
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label fw-bold">ADL (Air Dry Loss) %</label>
            <input type="text" inputmode="decimal" name="proximate_adl" class="form-control form-control-sm" id="proximate-adl" placeholder="Masukkan nilai ADL">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold text-primary">Residual Moisture (%)</label>
            <input type="text" class="form-control form-control-sm bg-light text-primary fw-bold" id="proximate-rm" readonly placeholder="Auto from Tab 1 (RM)">
        </div>
    </div>

    {{-- Tabel 1: Parameters --}}
    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3"><i class="fas fa-table me-2"></i>Parameters</h6>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm align-middle text-center" id="proximate-params-table">
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle">Parameters</th>
                    <th rowspan="2" class="align-middle">Unit</th>
                    <th colspan="4">Result</th>
                    <th rowspan="2" class="align-middle">Method</th>
                </tr>
                <tr>
                    <th>As Received (ar)</th>
                    <th>Air Dry Basis (adb)</th>
                    <th>Dry Basis (db)</th>
                    <th>DAF</th>
                </tr>
            </thead>
            <tbody>
                                <tr data-param="TM">
                    <td class="text-start fw-bold">Total Moisture</td>
                    <td>%</td>
                    <td class="prox-ar bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-adb text-muted">—</td>
                    <td class="prox-db text-muted">—</td>
                    <td class="prox-daf text-muted">—</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="IM">
                    <td class="text-start fw-bold">Moisture in Analysis</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db text-muted">—</td>
                    <td class="prox-daf text-muted">—</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="ASH">
                    <td class="text-start fw-bold">Ash Content</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf text-muted">—</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="VM">
                    <td class="text-start fw-bold">Volatile Matter</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="FC">
                    <td class="text-start fw-bold">Fixed Carbon</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="TS">
                    <td class="text-start fw-bold">Total Sulfur</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="GCV">
                    <td class="text-start fw-bold">Gross Calorific Value</td>
                    <td>cal/g</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tabel 2: Ultimate Analysis --}}
    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3"><i class="fas fa-table me-2"></i>Ultimate Analysis</h6>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle text-center" id="proximate-ultimate-table">
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle">Ultimate Analysis</th>
                    <th rowspan="2" class="align-middle">Unit</th>
                    <th colspan="4">Result</th>
                    <th rowspan="2" class="align-middle">Method</th>
                </tr>
                <tr>
                    <th>As Received (ar)</th>
                    <th>Air Dry Basis (adb)</th>
                    <th>Dry Basis (db)</th>
                    <th>DAF</th>
                </tr>
            </thead>
            <tbody>
                <tr data-param="C">
                    <td class="text-start fw-bold">Carbon</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="H">
                    <td class="text-start fw-bold">Hydrogen</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="N">
                    <td class="text-start fw-bold">Nitrogen</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
                <tr data-param="O">
                    <td class="text-start fw-bold">Oxygen</td>
                    <td>%</td>
                    <td class="prox-ar">-</td>
                    <td class="prox-adb bg-info bg-opacity-10 fw-bold">-</td>
                    <td class="prox-db">-</td>
                    <td class="prox-daf">-</td>
                    <td><input type="text" class="form-control form-control-sm border-0 text-center prox-method" placeholder="-" readonly></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
