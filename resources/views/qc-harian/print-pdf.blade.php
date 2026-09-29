<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan QC Harian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        body { background-color: #f8f9fa; }
        .page-container {
            width: 210mm; /* A4 width */
            margin: 20mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 15mm;
            position: relative;
        }
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .kop-surat h4 { margin: 0; font-weight: bold; text-transform: uppercase; }
        .kop-surat p { margin: 0; font-size: 12px; }
        
        table { font-size: 11px; width: 100%; }
        .table-bordered th, .table-bordered td { border: 1px solid #000 !important; }
        th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
        
        .page-break { page-break-before: always; height: 1px; display: block; clear: both; }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            background: #eee;
            padding: 5px;
            border-left: 4px solid #cc0000;
            -webkit-print-color-adjust: exact;
        }
        
        .chart-container {
            width: 100%;
            height: 350px;
            margin-bottom: 15px;
        }

        #loadingOverlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.9);
            z-index: 9999;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
        }
    </style>
</head>
<body>

    <div id="loadingOverlay">
        <div class="spinner-border text-danger mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
        <h4 class="fw-bold">Menyiapkan Laporan...</h4>
        <p class="text-muted">Sedang menggambar grafik dan mengonversi ke PDF, mohon tunggu sebentar.</p>
    </div>

    <!-- MAIN EXPORT CONTAINER -->
    <div id="pdfContent">
        @foreach($reportData as $index => $data)
            @php 
                $param = $data['parameter'];
                $logs = $data['logs'];
                $m = $data['mean'];
                $sd = $data['sd'];
            @endphp
            
            <div class="page-container">
                <!-- KOP SURAT -->
                <div class="kop-surat">
                    <h4>Laboratorium PT Sucofindo Cabang Cilacap</h4>
                </div>
                
                <h5 class="text-center fw-bold text-uppercase mb-2">LAPORAN PENGUJIAN HARIAN QC (IN-HOUSE)</h5>
                <p class="text-center mb-4" style="font-size:13px;">
                    <strong>Periode:</strong> {{ $periode }} &nbsp;|&nbsp; 
                    <strong>Batch:</strong> {{ $activeBatch->kode_batch }} &nbsp;|&nbsp; 
                    <strong>Parameter:</strong> {{ strtoupper($param->nama_parameter) }}
                </p>

                <!-- BAGIAN 1: RIWAYAT / REKAPAN -->
                @if($cetakRiwayat)
                    <div class="section-title">A. TABEL REKAPAN DATA PENGUJIAN HARIAN</div>
                    <table class="table table-bordered table-sm text-center align-middle mb-4">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Analis</th>
                                <th>Nilai D1</th>
                                <th>Nilai D2</th>
                                <th>Nilai Akhir (Control)</th>
                                <th>Status Evaluasi</th>
                                <th>Investigasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $i => $log)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d/m/Y') }}</td>
                                    <td>{{ $log->analis->username ?? 'Unknown' }}</td>
                                    <td>{{ number_format($log->nilai_d1, 4) }}</td>
                                    <td>{{ $log->nilai_d2 ? number_format($log->nilai_d2, 4) : '-' }}</td>
                                    <td class="fw-bold">{{ number_format($log->nilai_akhir, 2) }}</td>
                                    <td>{{ strtoupper($log->status_evaluasi) }}</td>
                                    <td style="font-size:9px;">{{ $log->catatan_investigasi ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted py-2">Belum ada data pengujian harian pada bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif

                <!-- BAGIAN 2: CONTROL CHART (Harus mulai di halaman baru agar grafik tidak terpotong, atau setidaknya diberi jeda) -->
                @if($cetakChart)
                    @if($cetakRiwayat && count($logs) > 5)
                        <!-- Jika ada tabel riwayat panjang, kita paksa page break sebelum grafik agar rapi -->
                        </div>
                        <div class="page-break"></div>
                        <div class="page-container">
                        <!-- KOP SURAT (Halaman Lanjutan) -->
                        <div class="kop-surat">
                            <h4>LABORATORIUM SIGMALAB</h4>
                            <p>Jl. Contoh Alamat No. 123, Kota Industri, 12345</p>
                        </div>
                        <p class="text-center mb-4" style="font-size:12px;">
                            <strong>Lanjutan:</strong> Control Chart Parameter {{ strtoupper($param->nama_parameter) }} ({{ $periode }})
                        </p>
                    @endif

                    <div class="section-title">{{ $cetakRiwayat ? 'B' : 'A' }}. GRAFIK CONTROL CHART</div>
                    
                    @if(count($logs) === 0)
                        <div class="alert alert-light text-center border p-3">Data pengujian kosong. Grafik tidak tersedia.</div>
                    @else
                        <!-- Data tersembunyi untuk dibaca JS -->
                        <div class="chart-data-source d-none" 
                            id="data_chart_{{ $param->parameter_uji_id }}"
                            data-mean="{{ $m }}"
                            data-sd="{{ $sd }}">
                            @json($logs)
                        </div>

                        <!-- Canvas Grafik -->
                        <div class="chart-container">
                            <canvas id="canvas_{{ $param->parameter_uji_id }}"></canvas>
                        </div>

                        <div class="section-title mt-4">{{ $cetakRiwayat ? 'C' : 'B' }}. TABEL KALKULASI LIMIT CONTROL CHART</div>
                        <table class="table table-bordered table-sm text-center align-middle" style="font-size: 10px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle">Tgl</th>
                                    <th rowspan="2" class="align-middle">Ke</th>
                                    <th>LCL (-3&sigma;)</th>
                                    <th>LWL (-2&sigma;)</th>
                                    <th>&mu; - 1&sigma;</th>
                                    <th>&mu; (Mean)</th>
                                    <th>&mu; + 1&sigma;</th>
                                    <th>UWL (+2&sigma;)</th>
                                    <th>UCL (+3&sigma;)</th>
                                    <th rowspan="2" class="align-middle">Control</th>
                                </tr>
                                <tr>
                                    <th>{{ number_format($m - 3*$sd, 2) }}</th>
                                    <th>{{ number_format($m - 2*$sd, 2) }}</th>
                                    <th>{{ number_format($m - 1*$sd, 2) }}</th>
                                    <th>{{ number_format($m, 2) }}</th>
                                    <th>{{ number_format($m + 1*$sd, 2) }}</th>
                                    <th>{{ number_format($m + 2*$sd, 2) }}</th>
                                    <th>{{ number_format($m + 3*$sd, 2) }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $idx => $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d/m/y') }}</td>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ number_format($m - 3*$sd, 2) }}</td>
                                        <td>{{ number_format($m - 2*$sd, 2) }}</td>
                                        <td>{{ number_format($m - 1*$sd, 2) }}</td>
                                        <td class="fw-bold">{{ number_format($m, 2) }}</td>
                                        <td>{{ number_format($m + 1*$sd, 2) }}</td>
                                        <td>{{ number_format($m + 2*$sd, 2) }}</td>
                                        <td>{{ number_format($m + 3*$sd, 2) }}</td>
                                        <td class="fw-bold">{{ number_format($log->nilai_akhir, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
            </div>
            
            <!-- Page break otomatis antar parameter (kecuali loop terakhir) -->
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Register DataLabels
            if (typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }

            // Fungsi render grafik per parameter
            const chartSources = document.querySelectorAll('.chart-data-source');
            
            for (let source of chartSources) {
                const logs = JSON.parse(source.textContent);
                const mean = parseFloat(source.getAttribute('data-mean'));
                const sd = parseFloat(source.getAttribute('data-sd'));
                const canvasId = source.id.replace('data_chart_', 'canvas_');
                const ctx = document.getElementById(canvasId).getContext('2d');
                
                const labels = [];
                const dataPoints = [];
                const pointColors = [];
                const pointRadii = [];
                
                logs.forEach(log => {
                    labels.push(log.tanggal_uji);
                    dataPoints.push(log.nilai_akhir);
                    
                    if (log.status_evaluasi === 'outlier') {
                        pointColors.push('rgba(220, 53, 69, 1)');
                        pointRadii.push(6);
                    } else if (log.status_evaluasi === 'warning') {
                        pointColors.push('rgba(255, 193, 7, 1)');
                        pointRadii.push(5);
                    } else {
                        pointColors.push('rgba(0, 0, 0, 1)');
                        pointRadii.push(3);
                    }
                });
                
                const len = Math.max(10, labels.length);
                if(labels.length < 10) {
                    for(let i = labels.length; i < 10; i++) labels.push('');
                }
                
                const arrMean = Array(len).fill(mean);
                const arrUCL = Array(len).fill(mean + 3*sd);
                const arrLCL = Array(len).fill(mean - 3*sd);
                const arrUWL = Array(len).fill(mean + 2*sd);
                const arrLWL = Array(len).fill(mean - 2*sd);
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Nilai QC',
                                data: dataPoints,
                                borderColor: 'rgba(0, 0, 0, 0.7)',
                                backgroundColor: 'transparent',
                                pointBackgroundColor: pointColors,
                                pointBorderColor: pointColors,
                                pointRadius: pointRadii,
                                borderWidth: 2,
                                tension: 0.2,
                                order: 0,
                                datalabels: {
                                    align: 'top',
                                    anchor: 'end',
                                    color: '#000',
                                    font: { weight: 'bold', size: 9 },
                                    formatter: function(value) { return parseFloat(value).toFixed(2); }
                                }
                            },
                            { label: 'Mean', data: arrMean, borderColor: 'rgba(25, 135, 84, 0.8)', borderWidth: 1.5, pointRadius: 0, order: 1, datalabels: { display: false } },
                            { label: 'UCL (+3SD)', data: arrUCL, borderColor: 'rgba(220, 53, 69, 0.8)', borderWidth: 1.5, pointRadius: 0, order: 2, datalabels: { display: false } },
                            { label: 'LCL (-3SD)', data: arrLCL, borderColor: 'rgba(220, 53, 69, 0.8)', borderWidth: 1.5, pointRadius: 0, order: 3, datalabels: { display: false } },
                            { label: 'UWL (+2SD)', data: arrUWL, borderColor: 'rgba(255, 193, 7, 0.8)', borderWidth: 1, borderDash: [5,5], pointRadius: 0, order: 4, datalabels: { display: false } },
                            { label: 'LWL (-2SD)', data: arrLWL, borderColor: 'rgba(255, 193, 7, 0.8)', borderWidth: 1, borderDash: [5,5], pointRadius: 0, order: 5, datalabels: { display: false } }
                        ]
                    },
                    options: {
                        animation: false, // MATIKAN ANIMASI AGAR LANGSUNG BISA DICETAK KE PDF!
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                        },
                        scales: {
                            y: { suggestedMin: mean - 4*sd, suggestedMax: mean + 4*sd }
                        }
                    }
                });
            }

            // Beri waktu sebentar (1 detik) untuk memastikan semua canvas ter-render sempurna
            setTimeout(async () => {
                const element = document.getElementById('pdfContent');
                const filename = 'Laporan_QC_Harian_{{ str_replace(" ", "_", $periode) }}.pdf';
                
                const opt = {
                    margin:       0,
                    filename:     filename,
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2, useCORS: true },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };
                
                await html2pdf().set(opt).from(element).save();
                
                // Ubah overlay menjadi tombol tutup
                const overlay = document.getElementById('loadingOverlay');
                overlay.innerHTML = `
                    <div class="text-success mb-3"><i class="fas fa-check-circle" style="font-size: 4rem;"></i></div>
                    <h4 class="fw-bold">Download Selesai!</h4>
                    <p class="text-muted">File PDF telah berhasil diunduh.</p>
                    <button class="btn btn-danger mt-3 px-4" onclick="window.close()">Tutup Halaman Ini</button>
                `;
            }, 1000);
        });
    </script>
</body>
</html>
