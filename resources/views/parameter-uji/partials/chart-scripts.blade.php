            <!-- Chart Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Line Chart: {{ $selectedParameter->nama_parameter }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height:60vh; width:100%">
                        <canvas id="controlChart"></canvas>
                    </div>
                </div>
            </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

@if(isset($chartData) && count($chartData['labels']) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('controlChart').getContext('2d');
        
        // Prepare Data
        const labels = {!! json_encode($chartData['labels']) !!};
        const values = {!! json_encode($chartData['values']) !!};
        const statuses = {!! json_encode($chartData['statuses']) !!};
        const kodeAturan = {!! json_encode($chartData['kodeAturan']) !!};
        
        // Color Mapping
        const pointColors = statuses.map(status => {
            if (status === 'merah') return '#dc3545'; // Danger
            if (status === 'kuning') return '#ffc107'; // Warning
            return '#28a745'; // Success / In-Control
        });

        Chart.register(ChartDataLabels);
        const controlChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Hasil Uji',
                    data: values,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    pointBackgroundColor: pointColors,
                    pointBorderColor: pointColors,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        align: 'top',
                        color: pointColors,
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {
                            let rules = kodeAturan[context.dataIndex];
                            if(rules && rules !== '') {
                                return rules; // Tampilkan kode pelanggaran (misal "1(3s)")
                            }
                            return '';
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                }
                                
                                let statusMsg = statuses[context.dataIndex];
                                let aturan = kodeAturan[context.dataIndex];
                                
                                let tooltipTexts = [label];
                                
                                if(statusMsg === 'merah') {
                                    tooltipTexts.push('Status: TOLAK (Out of Control)');
                                } else if (statusMsg === 'kuning') {
                                    tooltipTexts.push('Status: WARNING');
                                } else {
                                    tooltipTexts.push('Status: TERIMA (In Control)');
                                }
                                
                                if(aturan && aturan !== '') {
                                    tooltipTexts.push('Melanggar: ' + aturan);
                                }
                                
                                return tooltipTexts;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Pengujian Ke-'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Nilai Hasil'
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
