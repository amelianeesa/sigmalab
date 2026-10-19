@if(($jenisGrafik ?? 'in_house') === 'crm')
<!-- Table Card CRM -->
<div class="col-xl-9 col-md-6 mb-4">
    <div class="card shadow h-100">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Evaluasi Sertifikat CRM</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                <table class="table table-bordered table-striped table-hover mb-0 text-center" style="font-size: 0.85rem;">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pengujian Ke-</th>
                            <th class="text-primary font-weight-bold" title="Nilai True Value Sertifikat CRM">True Value</th>
                            <th class="text-secondary" title="Uncertainty (U)">Uncertainty (U)</th>
                            <th class="text-danger" title="Batas Bawah Penerimaan">Batas Bawah</th>
                            <th class="text-danger" title="Batas Atas Penerimaan">Batas Atas</th>
                            <th class="bg-primary text-white">Hasil Analisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($stats['evaluations']))
                            @foreach($stats['evaluations'] as $index => $eval)
                            <tr>
                                <td>{{ $hasilList[$index]->created_at->format('d/m/Y') }}</td>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-primary font-weight-bold">{{ number_format($eval['cert_value'], 4, ',', '.') }}</td>
                                <td class="text-secondary">±{{ number_format($eval['cert_u'], 4, ',', '.') }}</td>
                                <td class="text-danger">{{ number_format($eval['batas_bawah'], 4, ',', '.') }}</td>
                                <td class="text-danger">{{ number_format($eval['batas_atas'], 4, ',', '.') }}</td>
                                <td class="font-weight-bold bg-info text-white">
                                    {{ number_format($hasilList[$index]->nilai_hasil, 4, ',', '.') }}
                                </td>
                                <td>
                                    @if($eval['status'] === 'Terima')
                                        <span class="badge bg-success">Diterima</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr><td colspan="8">Data evaluasi CRM tidak tersedia.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<!-- Table Card Control Chart Limit -->
<div class="col-xl-9 col-md-6 mb-4">
    <div class="card shadow h-100">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Limit Control Chart In-house</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                <table class="table table-bordered table-striped table-hover mb-0 text-center" style="font-size: 0.85rem;">
                    <thead class="table-light sticky-top">
                        @php 
                            $validIndex = 1; 
                            $fixedLcl = $selectedParameter->lcl ?? 0;
                            $fixedLwl = $selectedParameter->uwl_bawah ?? 0;
                            $fixedMean = $selectedParameter->mean ?? 0;
                            $fixedUwl = $selectedParameter->uwl_atas ?? 0;
                            $fixedUcl = $selectedParameter->ucl ?? 0;
                            $fixedSd = $selectedParameter->sd ?? 0;
                            $minus1Sd = $fixedMean - $fixedSd;
                            $plus1Sd = $fixedMean + $fixedSd;
                        @endphp
                        <tr>
                            <th>Tanggal</th>
                            <th>Pengujian Ke-</th>
                            <th class="text-danger" title="Lower Control Limit (-3SD)">LCL<br>({{ number_format($fixedLcl, 2) }})</th>
                            <th class="text-warning text-dark" title="Lower Warning Limit (-2SD)">LWL<br>({{ number_format($fixedLwl, 2) }})</th>
                            <th class="text-success" title="Mean - 1SD">μ-1σ<br>({{ number_format($minus1Sd, 2) }})</th>
                            <th class="text-primary font-weight-bold">μ<br>({{ number_format($fixedMean, 2) }})</th>
                            <th class="text-success" title="Mean + 1SD">μ+1σ<br>({{ number_format($plus1Sd, 2) }})</th>
                            <th class="text-warning text-dark" title="Upper Warning Limit (+2SD)">UWL<br>({{ number_format($fixedUwl, 2) }})</th>
                            <th class="text-danger" title="Upper Control Limit (+3SD)">UCL<br>({{ number_format($fixedUcl, 2) }})</th>
                            <th class="bg-primary text-white">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilList as $hasil)
                        @if($hasil->status_berketerimaan !== 'gagal_duplo')
                        <tr>
                            <td>{{ $hasil->created_at->format('d/m/Y') }}</td>
                            <td>{{ $validIndex++ }}</td>
                            <td class="text-danger">{{ number_format($fixedLcl, 2, ',', '.') }}</td>
                            <td class="text-warning text-dark">{{ number_format($fixedLwl, 2, ',', '.') }}</td>
                            <td class="text-success">{{ number_format($minus1Sd, 2, ',', '.') }}</td>
                            <td class="text-primary font-weight-bold">{{ number_format($fixedMean, 2, ',', '.') }}</td>
                            <td class="text-success">{{ number_format($plus1Sd, 2, ',', '.') }}</td>
                            <td class="text-warning text-dark">{{ number_format($fixedUwl, 2, ',', '.') }}</td>
                            <td class="text-danger">{{ number_format($fixedUcl, 2, ',', '.') }}</td>
                            <td class="font-weight-bold bg-info text-white">
                                {{ number_format($hasil->nilai_hasil, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
