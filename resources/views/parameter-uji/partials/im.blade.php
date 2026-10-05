@include('parameter-uji.partials.limit-eval-alert')

<style>
    .im-page { font-size: 0.82rem; }

    .im-page .card-body { padding: 10px !important; }
    .im-page .card-header {
        padding: 8px 12px !important;
        background: #fff;
    }
    .im-page .card-header h6 {
        font-size: 0.85rem !important;
        margin: 0;
    }
    .im-page .btn { font-size: 0.78rem; }

    .im-page .alert {
        font-size: 0.8rem;
        padding: 6px 12px;
        margin-bottom: 10px;
    }

    /* Tabel (termasuk partial stats, limit, westgard) */
    .im-page .table th,
    .im-page .table td {
        padding: 6px 8px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .im-page .table thead th {
        font-size: 0.72rem !important;
        background-color: #1b3152 !important;
        color: #fff !important;
        border-color: #fff !important;
    }

    /* Warna kelompok kolom (kalem) */
    .im-page .table thead th.th-manual { background-color: #2f5d9e !important; color: #fff !important; }
    .im-page .table thead th.th-auto   { background-color: #6b7a90 !important; color: #fff !important; }
    .im-page .table thead th.th-diff   { background-color: #d9a21b !important; color: #1b1b1b !important; }
    .im-page .table thead th.th-final  { background-color: #2b7a4b !important; color: #fff !important; }
    .im-page .table thead th.th-hint {
        background-color: #e9edf3 !important;
        color: #5a6678 !important;
        font-weight: 500;
        font-size: 0.66rem !important;
    }

    .im-page .badge { font-size: 0.65rem; }
    .im-page .chart-area { height: 280px !important; }

    @media (max-width: 767.98px) {
        .im-page .chart-area { height: 220px !important; }
        .im-page .table th,
        .im-page .table td {
            padding: 5px 6px !important;
            font-size: 0.68rem !important;
        }
    }
</style>

<div class="im-page">

    @if(!isset($selectedParameter) || !$selectedParameter)
        <div class="alert alert-info shadow-sm border-0" role="alert">
            <i class="fas fa-info-circle me-1"></i> Silakan pilih Parameter Uji terlebih dahulu untuk menampilkan Inhouse Control.
        </div>
    @else

        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-table me-1"></i> Tabel Raw Data Logger (Duplo Testing)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle">
                        <thead class="sticky-top">
                            <tr>
                                <th rowspan="2" class="align-middle">Tanggal</th>
                                <th rowspan="2" class="align-middle">Pengujian Ke-</th>
                                <th rowspan="2" class="align-middle">Kode Sampel</th>
                                <th rowspan="2" class="align-middle">Dish No.</th>
                                <th class="th-manual" title="Massa cawan kosong">M1</th>
                                <th class="th-auto" title="M1 + A">M2</th>
                                <th class="th-manual" title="Massa sampel utuh">A</th>
                                <th class="th-manual" title="Massa cawan + sampel kering">M3</th>
                                <th class="th-auto" title="M3 - M1">B</th>
                                <th rowspan="2" class="align-middle">M%</th>
                                <th colspan="2" class="th-diff">Absolute Diff.</th>
                                <th rowspan="2" class="align-middle th-final">Average M%</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="th-hint">Manual (Biru) &amp; Auto (Abu)</th>
                                <th class="th-diff">Value</th>
                                <th class="th-diff">Eval (&le; 0.10)</th>
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
                                    <td rowspan="2" class="align-middle fw-bold {{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'text-danger text-decoration-line-through' : 'text-success' }}">
                                        {{ number_format($hasil->nilai_hasil, 4) }}
                                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                                            <br><span class="badge bg-danger mt-1">REJECTED</span>
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
                                    <td colspan="13" class="text-center text-muted py-3">Belum ada data pengujian Inherent Moisture.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(isset($chartData) && count($chartData['labels']) > 0)

            <div class="row g-3 mb-3">
                @include('parameter-uji.partials.stats-cards')
                @include('parameter-uji.partials.limit-table')
            </div>

            @include('parameter-uji.partials.westgard-table')
            @include('parameter-uji.partials.chart-scripts')

        @endif
    @endif

    @include('parameter-uji.partials.override-modal')
    @include('qc-uji-banding.print.footer_ttd', ['idPrefix' => 'im', 'kodeDokumen' => 'FOR/COAL-OPS/026', 'rev' => '03', 'tglBerlaku' => '16/08/2021'])
</div>