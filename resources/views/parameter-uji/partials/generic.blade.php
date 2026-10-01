@include('parameter-uji.partials.limit-eval-alert')

<style>
    .pu-generic-container { font-size: 0.82rem; }

    .card-header-custom {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem !important;
    }

    .form-label { font-size: 0.75rem !important; font-weight: 600; color: #334155; }

    .flatpickr-input { font-size: 0.8rem !important; }
    .flatpickr-calendar { font-size: 0.85rem; }

    .filter-select {
        position: relative;
        font-size: 0.8rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.65rem;
        font-size: 0.8rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .filter-select-trigger:after {
        content: "";
        width: 0; height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6c757d;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .filter-select.open .filter-select-trigger {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .filter-select-options {
        display: none;
        position: absolute;
        top: 100%; left: 0; right: 0;
        z-index: 1050;
        margin-top: 2px;
        max-height: 220px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        list-style: none;
        padding: 4px 0;
    }
    .filter-select.open .filter-select-options { display: block; }
    .filter-select-options li { padding: 6px 10px; font-size: 0.8rem; cursor: pointer; }
    .filter-select-options li:hover { background-color: #f1f3f5; }
    .filter-select-options li.selected { background-color: #0d6efd; color: #fff; }

    .pu-summary-label { font-size: 0.7rem !important; }
    .pu-summary-value { font-size: 0.95rem !important; }
    .pu-summary-badges .badge { font-size: 0.7rem !important; }

    .pu-table-header th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-size: 0.7rem !important;
        vertical-align: middle !important;
    }
    .pu-table td, .pu-table th {
        font-size: 0.75rem !important;
        vertical-align: middle !important;
    }

    .scroll-hint-pu {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }

    @media (max-width: 768px) {
        .pu-table td, .pu-table th { font-size: 0.65rem !important; }
        .scroll-hint-pu { display: block; }
    }

    @media (max-width: 575.98px) {
        .pu-filter-actions { flex-direction: column; }
        .pu-filter-actions .btn { width: 100%; }
    }
</style>

<div class="pu-generic-container">

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header card-header-custom py-2">
            <i class="fas fa-filter me-1"></i> Filter Data
        </div>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="tab" value="{{ request('tab', 'in_house') }}">

                <div class="col-md-3">
                    <label for="jenis_grafik" class="form-label">Jenis Grafik</label>
                    @php
                        $jenisGrafikLabels = [
                            'in_house' => 'In-House (Levy-Jennings)',
                            'crm' => 'CRM (Akurasi % Recovery)',
                        ];
                        $selectedJenisGrafik = request('jenis_grafik', 'in_house');
                    @endphp
                    <div class="filter-select" id="selectJenisGrafik">
                        <input type="hidden" name="jenis_grafik" id="jenisGrafikInput" value="{{ $selectedJenisGrafik }}" required>
                        <button type="button" class="filter-select-trigger">{{ $jenisGrafikLabels[$selectedJenisGrafik] ?? $jenisGrafikLabels['in_house'] }}</button>
                        <ul class="filter-select-options">
                            @foreach($jenisGrafikLabels as $value => $label)
                                <li data-value="{{ $value }}" class="{{ $selectedJenisGrafik === $value ? 'selected' : '' }}">{{ $label }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="text" class="form-control flatpickr-date" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="text" class="form-control flatpickr-date" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                </div>
                <div class="col-md-3 d-flex gap-2 pu-filter-actions">
                    <button type="submit" class="btn btn-primary flex-grow-1" style="font-size: 0.8rem;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($selectedParameter)
                        <button type="button" class="btn btn-danger flex-grow-1" style="font-size: 0.8rem;" onclick="cetakPDF()">
                            <i class="fas fa-file-pdf"></i> Cetak
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($selectedParameter)
    <!-- Form Cetak PDF (Hidden) -->
    <form id="formCetak" action="{{ route('parameter-uji.cetak-control-chart', $selectedParameter->parameter_uji_id) }}" method="POST" target="_blank" style="display: none;">
        @csrf
        <input type="hidden" name="parameter_uji_id" value="{{ request('parameter_uji_id') }}">
        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
        <input type="hidden" name="chart_image" id="chart_image">
    </form>
    @endif

    @if(!isset($selectedParameter) || !$selectedParameter)
        <!-- No Parameter Selected -->
        <div class="alert alert-info shadow-sm" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-info-circle me-2"></i> Silakan pilih Parameter Uji terlebih dahulu untuk menampilkan Control Chart.
        </div>
    @else
        <!-- Chart Card -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header card-header-custom d-flex flex-row align-items-center justify-content-between py-2">
                <span><i class="fas fa-chart-line me-1"></i> Levy-Jennings Chart: {{ $selectedParameter->nama_parameter }}</span>
            </div>
            <div class="card-body">
                @if(isset($chartData) && count($chartData['labels']) > 0)
                    <div class="chart-area" style="position: relative; height:55vh; width:100%">
                        <canvas id="controlChart"></canvas>
                    </div>
                @else
                    <div class="alert alert-warning mb-0" style="font-size: 0.8rem;">
                        Tidak ada data yang ditemukan untuk periode dan parameter tersebut.
                    </div>
                @endif
            </div>
        </div>

        @if(isset($chartData) && count($chartData['labels']) > 0)
            <!-- Summary Card -->
            <div class="row g-3 mb-1">
                <div class="col-md-6">
                    <div class="card border-start border-primary border-4 shadow-sm h-100">
                        <div class="card-body py-2 px-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-uppercase fw-bold text-primary pu-summary-label mb-1">
                                        Statistik Parameter
                                    </div>
                                    <div class="fw-bold text-dark pu-summary-value">
                                        Mean (µ): {{ number_format($chartData['lines']['mean'], 4) }}
                                    </div>
                                    <div class="fw-bold text-dark pu-summary-value">
                                        SD (σ): {{ number_format(abs($chartData['lines']['mean'] - $chartData['lines']['plus1sd']), 4) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-muted opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-start border-info border-4 shadow-sm h-100">
                        <div class="card-body py-2 px-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-uppercase fw-bold text-info pu-summary-label mb-1">
                                        Distribusi Hasil
                                    </div>
                                    @php
                                        $inControl = collect($chartData['statuses'])->filter(fn($s) => $s === 'hijau')->count();
                                        $warning = collect($chartData['statuses'])->filter(fn($s) => $s === 'kuning')->count();
                                        $outOfControl = collect($chartData['statuses'])->filter(fn($s) => $s === 'merah')->count();
                                    @endphp
                                    <div class="mb-0 pu-summary-badges">
                                        <span class="badge bg-success">In-Control: {{ $inControl }}</span>
                                        <span class="badge bg-warning text-dark">Warning: {{ $warning }}</span>
                                        <span class="badge bg-danger">Out-of-Control: {{ $outOfControl }}</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-pie fa-2x text-muted opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <!-- Rules Card -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header card-header-custom py-2">Aturan Westgard Aktif</div>
                        <div class="card-body" style="font-size: 0.78rem;">
                            @if($selectedParameter->westgardRules && $selectedParameter->westgardRules->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($selectedParameter->westgardRules as $rule)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0" style="font-size: 0.78rem;">
                                            {{ $rule->nama_aturan ?? $rule->kode_aturan }}
                                            <span class="badge bg-primary rounded-pill" style="font-size: 0.68rem;">{{ $rule->kode_aturan }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0">Tidak ada aturan spesifik yang ditetapkan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="col-md-8 mb-3">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header card-header-custom py-2">Tabel Control Chart In-house</div>
                        <div class="card-body p-0">
                            <div class="scroll-hint-pu px-3 pt-2"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered table-striped table-hover mb-0 text-center pu-table">
                                    <thead class="pu-table-header sticky-top">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Pengujian Ke-</th>
                                            <th title="Lower Control Limit (-3SD)">LCL</th>
                                            <th title="Lower Warning Limit (-2SD)">LWL</th>
                                            <th title="Mean - 1SD">µ-1σ</th>
                                            <th>µ</th>
                                            <th title="Mean + 1SD">µ+1σ</th>
                                            <th title="Upper Warning Limit (+2SD)">UWL</th>
                                            <th title="Upper Control Limit (+3SD)">UCL</th>
                                            <th>Nilai</th>
                                            <th>Status Akhir</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hasilList as $index => $hasil)
                                        <tr>
                                            <td>{{ $hasil->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-danger">{{ number_format($chartData['lines']['minus3sd'], 4) }}</td>
                                            <td class="text-warning text-dark">{{ number_format($chartData['lines']['minus2sd'], 4) }}</td>
                                            <td class="text-success">{{ number_format($chartData['lines']['minus1sd'], 4) }}</td>
                                            <td class="text-primary fw-bold">{{ number_format($chartData['lines']['mean'], 4) }}</td>
                                            <td class="text-success">{{ number_format($chartData['lines']['plus1sd'], 4) }}</td>
                                            <td class="text-warning text-dark">{{ number_format($chartData['lines']['plus2sd'], 4) }}</td>
                                            <td class="text-danger">{{ number_format($chartData['lines']['plus3sd'], 4) }}</td>
                                            <td class="fw-bold {{ $hasil->status_berketerimaan == 'outlier' ? 'text-danger' : ($hasil->kode_aturan_dilanggar == '1-2s' ? 'text-warning' : 'text-success') }}">
                                                {{ number_format($hasil->nilai_hasil, 4) }}
                                            </td>
                                            <td>
                                                @if($hasil->override_status)
                                                    <span class="badge {{ $hasil->override_status == 'outlier' ? 'bg-danger' : ($hasil->override_status == 'warning' ? 'bg-warning text-dark' : 'bg-success') }}" style="font-size: 0.65rem;">
                                                        {{ strtoupper($hasil->override_status) }} 
                                                        @if($hasil->override_kode) ({{ $hasil->override_kode }}) @endif
                                                        <i class="fas fa-user-edit ms-1" title="Di-override Manual"></i>
                                                    </span>
                                                @else
                                                    <span class="badge {{ $hasil->status_berketerimaan == 'outlier' ? 'bg-danger' : ($hasil->kode_aturan_dilanggar == '1-2s' ? 'bg-warning text-dark' : 'bg-success') }}" style="font-size: 0.65rem;">
                                                        {{ $hasil->status_berketerimaan == 'outlier' ? 'OUTLIER' : 'INLIER' }}
                                                        @if($hasil->kode_aturan_dilanggar) ({{ $hasil->kode_aturan_dilanggar }}) @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary py-0" style="font-size: 0.72rem;" onclick="openOverrideModal({{ $hasil->hasil_uji_id }}, '{{ $hasil->override_status ?? $hasil->status_berketerimaan }}', '{{ $hasil->override_kode ?? $hasil->kode_aturan_dilanggar }}')" title="Timpa Manual">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

<!-- Modal Override Westgard -->
<div class="modal fade" id="overrideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="overrideForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1b3152; color: #fff;">
                    <h5 class="modal-title" style="font-size: 0.95rem;">Override Status Westgard</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 0.82rem;">
                    <p class="text-muted small mb-3">Fitur ini digunakan jika analis/lab memiliki interpretasi aturan Westgard yang berbeda dari kalkulasi sistem otomatis.</p>

                    <div class="mb-3">
                        <label class="form-label">Status Baru</label>
                        @php
                            $overrideStatusLabels = [
                                'inlier' => 'INLIER (Normal)',
                                'warning' => 'WARNING (Peringatan)',
                                'outlier' => 'OUTLIER (Tolak)',
                            ];
                        @endphp
                        <div class="filter-select" id="selectOverrideStatus">
                            <input type="hidden" name="override_status" id="override_status" value="inlier" required>
                            <button type="button" class="filter-select-trigger">INLIER (Normal)</button>
                            <ul class="filter-select-options">
                                @foreach($overrideStatusLabels as $value => $label)
                                    <li data-value="{{ $value }}" class="{{ $value === 'inlier' ? 'selected' : '' }}">{{ $label }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggaran (Opsional)</label>
                        <input type="text" class="form-control" name="override_kode" id="override_kode" placeholder="Misal: 1-2s, 1-3s, 2-2s..." style="font-size: 0.8rem;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 0.8rem;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="font-size: 0.8rem;">Simpan Override</button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true
        });

        document.querySelectorAll('.filter-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.filter-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.filter-select-options li');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.filter-select.open').forEach(function (other) {
                    if (other !== wrapper) other.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });

            options.forEach(function (li) {
                li.addEventListener('click', function () {
                    hiddenInput.value = li.getAttribute('data-value');
                    trigger.textContent = li.textContent;
                    options.forEach(function (o) { o.classList.remove('selected'); });
                    li.classList.add('selected');
                    wrapper.classList.remove('open');
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });
    });
</script>

@if(isset($chartData) && count($chartData['labels']) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('controlChart').getContext('2d');

        // Prepare Data
        const labels = {!! json_encode($chartData['labels']) !!};
        const values = {!! json_encode($chartData['values']) !!};
        const statuses = {!! json_encode($chartData['statuses']) !!};
        const kodeAturan = {!! json_encode($chartData['kodeAturan']) !!};

        const lines = {!! json_encode($chartData['lines']) !!};

        // Color Mapping
        const pointColors = statuses.map(status => {
            if (status === 'merah') return '#dc3545'; // Danger
            if (status === 'kuning') return '#ffc107'; // Warning
            return '#28a745'; // Success / In-Control
        });

        const meanData = labels.map(() => lines.mean);
        const p1sdData = labels.map(() => lines.plus1sd);
        const p2sdData = labels.map(() => lines.plus2sd);
        const p3sdData = labels.map(() => lines.plus3sd);
        const m1sdData = labels.map(() => lines.minus1sd);
        const m2sdData = labels.map(() => lines.minus2sd);
        const m3sdData = labels.map(() => lines.minus3sd);

        const controlChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nilai Ukur',
                        data: values,
                        borderColor: '#4e73df',
                        backgroundColor: pointColors,
                        pointBackgroundColor: pointColors,
                        pointBorderColor: pointColors,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: false,
                        tension: 0,
                        borderWidth: 2,
                        zIndex: 10
                    },
                    {
                        label: 'Mean',
                        data: meanData,
                        borderColor: '#0d6efd',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    @if(request('jenis_grafik') != 'crm')
                    {
                        label: '+1 SD',
                        data: p1sdData,
                        borderColor: '#198754',
                        borderWidth: 1,
                        borderDash: [3, 3],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '-1 SD',
                        data: m1sdData,
                        borderColor: '#198754',
                        borderWidth: 1,
                        borderDash: [3, 3],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '+2 SD (UWL)',
                        data: p2sdData,
                        borderColor: '#fd7e14',
                        borderWidth: 1,
                        borderDash: [4, 4],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '-2 SD (LWL)',
                        data: m2sdData,
                        borderColor: '#fd7e14',
                        borderWidth: 1,
                        borderDash: [4, 4],
                        pointRadius: 0,
                        fill: false
                    },
                    @endif
                    {
                        label: '{{ request('jenis_grafik') == 'crm' ? 'Batas Atas Recovery' : '+3 SD (UCL)' }}',
                        data: p3sdData,
                        borderColor: '#dc3545',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '{{ request('jenis_grafik') == 'crm' ? 'Batas Bawah Recovery' : '-3 SD (LCL)' }}',
                        data: m3sdData,
                        borderColor: '#dc3545',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: { size: 10 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label === 'Nilai Ukur') {
                                    let idx = context.dataIndex;
                                    let val = context.parsed.y;
                                    let info = 'Nilai: ' + val;
                                    if (kodeAturan[idx]) {
                                        info += ' | Pelanggaran: ' + kodeAturan[idx];
                                    }
                                    return info;
                                }
                                return label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal / Urutan',
                            font: { size: 11 }
                        },
                        ticks: { font: { size: 10 } }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Nilai',
                            font: { size: 11 }
                        },
                        ticks: { font: { size: 10 } },
                        suggestedMin: lines.minus3sd - (lines.plus1sd - lines.mean),
                        suggestedMax: lines.plus3sd + (lines.plus1sd - lines.mean)
                    }
                }
            }
        });
    });

    function cetakPDF() {
        var canvas = document.getElementById('controlChart');
        if (canvas) {
            var base64 = canvas.toDataURL('image/png');
            document.getElementById('chart_image').value = base64;
        }
        document.getElementById('formCetak').submit();
    }
    function openOverrideModal(hasilUjiId, currentStatus, currentKode) {
        var modal = new bootstrap.Modal(document.getElementById('overrideModal'));

        document.getElementById('overrideForm').action = '/hasil-uji/' + hasilUjiId + '/override';

        var overrideWrapper = document.getElementById('selectOverrideStatus');
        var overrideInput = document.getElementById('override_status');
        var overrideTrigger = overrideWrapper.querySelector('.filter-select-trigger');
        var overrideOptions = overrideWrapper.querySelectorAll('.filter-select-options li');

        var targetValue = 'inlier';
        if (currentStatus === 'outlier') targetValue = 'outlier';
        else if (currentStatus === 'warning' || currentKode === '1-2s') targetValue = 'warning';

        overrideOptions.forEach(function (li) {
            const isMatch = li.getAttribute('data-value') === targetValue;
            li.classList.toggle('selected', isMatch);
            if (isMatch) {
                overrideInput.value = targetValue;
                overrideTrigger.textContent = li.textContent;
            }
        });

        document.getElementById('override_kode').value = currentKode || '';

        modal.show();
    }
</script>
@endif
@endpush