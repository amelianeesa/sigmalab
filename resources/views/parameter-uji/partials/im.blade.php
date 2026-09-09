@include('parameter-uji.partials.limit-eval-alert')

<div>

    @if(!isset($selectedParameter) || !$selectedParameter)
        <!-- No Parameter Selected -->
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i> Silakan pilih Parameter Uji terlebih dahulu untuk menampilkan Inhouse Control.
        </div>
    @else
        
        <!-- Raw Data Logger (Duplo) -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Raw Data Logger (Duplo Testing)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle" style="font-size: 0.8rem;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th rowspan="2" class="align-middle">Tanggal</th>
                                <th rowspan="2" class="align-middle">Pengujian Ke-</th>
                                <th rowspan="2" class="align-middle">Kode Sampel</th>
                                <th rowspan="2" class="align-middle">Dish No.</th>
                                <th class="bg-primary text-white" title="Massa cawan kosong">M1</th>
                                <th class="bg-secondary text-white" title="M1 + A">M2</th>
                                <th class="bg-primary text-white" title="Massa sampel utuh">A</th>
                                <th class="bg-primary text-white" title="Massa cawan + sampel kering">M3</th>
                                <th class="bg-secondary text-white" title="M3 - M1">B</th>
                                <th rowspan="2" class="align-middle">M%</th>
                                <th colspan="2" class="bg-warning text-dark">Absolute Diff.</th>
                                <th rowspan="2" class="align-middle bg-success text-white">Average M%</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="bg-light text-muted small py-1">Manual (Biru) &amp; Auto (Abu)</th>
                                <th class="bg-warning text-dark">Value</th>
                                <th class="bg-warning text-dark">Eval (<= 0.10)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasilList as $index => $hasil)
                                @php
                                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                    $M1_1 = $dm['M1_D1'] ?? '-';
                                    $A_1  = $dm['A_D1'] ?? '-';
                                    $M3_1 = $dm['M3_D1'] ?? '-';
                                    $M2_1 = $dm['M2_D1_Result'] ?? (is_numeric($M1_1) && is_numeric($A_1) ? number_format($M1_1 + $A_1, 4) : '-');
                                    $B1   = is_numeric($M1_1) && is_numeric($M3_1) ? number_format($M3_1 - $M1_1, 4) : '-';
                                    $M_1  = $dm['M_D1_Result'] ?? '-';
                                    
                                    $M1_2 = $dm['M1_D2'] ?? '-';
                                    $A_2  = $dm['A_D2'] ?? '-';
                                    $M3_2 = $dm['M3_D2'] ?? '-';
                                    $M2_2 = $dm['M2_D2_Result'] ?? (is_numeric($M1_2) && is_numeric($A_2) ? number_format($M1_2 + $A_2, 4) : '-');
                                    $B2   = is_numeric($M1_2) && is_numeric($M3_2) ? number_format($M3_2 - $M1_2, 4) : '-';
                                    $M_2  = $dm['M_D2_Result'] ?? '-';
                                    
                                    $absDiff = $dm['Absolute_Diff'] ?? '-';
                                    
                                    // Validasi dinamis 0.09 + (0.1 * Average_M)
                                    $avgM = is_numeric($M_1) && is_numeric($M_2) ? ($M_1 + $M_2) / 2 : 0;
                                    $tolDin = 0.09 + (0.1 * $avgM);
                                    $isYes = is_numeric($absDiff) && $absDiff <= $tolDin;
                                @endphp
                                
                                <!-- Dish 1 Row -->
                                <tr>
                                    <td rowspan="2" class="align-middle fw-bold">{{ $hasil->created_at->format('d/m/Y') }}</td>
                                    <td rowspan="2" class="align-middle fw-bold">{{ $index + 1 }}</td>
                                    <td rowspan="2" class="align-middle">{{ $hasil->kegiatan->kode_sampel ?? '-' }}</td>
                                    
                                    <td class="text-primary fw-bold">Dish 1</td>
                                    <td>{{ $M1_1 }}</td>
                                    <td class="bg-light">{{ $M2_1 }}</td>
                                    <td class="fw-bold">{{ $A_1 }}</td>
                                    <td>{{ $M3_1 }}</td>
                                    <td class="bg-light">{{ $B1 }}</td>
                                    <td class="fw-bold text-primary">{{ is_numeric($M_1) ? number_format($M_1, 4) : $M_1 }}</td>
                                    
                                    <td rowspan="2" class="align-middle fw-bold">{{ is_numeric($absDiff) ? number_format($absDiff, 4) : $absDiff }}</td>
                                    <td rowspan="2" class="align-middle">
                                        @if(is_numeric($absDiff))
                                            @if($isYes)
                                                <span class="badge bg-success">YES</span>
                                            @else
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td rowspan="2" class="align-middle fw-bold fs-6 {{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'text-danger text-decoration-line-through' : 'text-success' }}">
                                        {{ number_format($hasil->nilai_hasil, 4) }}
                                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                                            <br><small class="badge bg-danger mt-1">REJECTED</small>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Dish 2 Row -->
                                <tr>
                                    <td class="text-primary fw-bold">Dish 2</td>
                                    <td>{{ $M1_2 }}</td>
                                    <td class="bg-light">{{ $M2_2 }}</td>
                                    <td class="fw-bold">{{ $A_2 }}</td>
                                    <td>{{ $M3_2 }}</td>
                                    <td class="bg-light">{{ $B2 }}</td>
                                    <td class="fw-bold text-primary">{{ is_numeric($M_2) ? number_format($M_2, 4) : $M_2 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4">Belum ada data pengujian Inherent Moisture.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(isset($chartData) && count($chartData['labels']) > 0)
            
                          <div class="row">
                @include('parameter-uji.partials.stats-cards')
                @include('parameter-uji.partials.limit-table')
            </div>
            
            @include('parameter-uji.partials.westgard-table')
            @include('parameter-uji.partials.chart-scripts')

        @endif
    @endif
    
    @include('parameter-uji.partials.override-modal')
</div>
