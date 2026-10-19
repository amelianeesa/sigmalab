@include('parameter-uji.partials.limit-eval-alert')

<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
            @if($selectedParameter)
                <li class="breadcrumb-item active" aria-current="page">{{ $selectedParameter->nama_parameter }}</li>
            @endif
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ ($jenisGrafik ?? 'in_house') == 'crm' ? 'Sertifikat Pabrik (CRM)' : 'Inhouse Control' }}: Bias Test VM (Pt)</h1>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ url()->
            <input type="hidden" name="tab" value="{{ $jenisGrafik }}">
current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="parameter_uji_id" class="form-label">Parameter Uji</label>
                    <select class="form-select" id="parameter_uji_id" name="parameter_uji_id" required>
                        <option value="">-- Pilih Parameter --</option>
                        @foreach($parameterList as $param)
                            <option value="{{ $param->parameter_uji_id }}" {{ request('parameter_uji_id') == $param->parameter_uji_id ? 'selected' : '' }}>
                                {{ $param->nama_parameter }} ({{ $param->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($selectedParameter)
                        <a href="{{ route('parameter-uji.cetak-control-chart', ['parameter_uji' => request()->route('parameter_uji') ?? $selectedParameter->parameter_uji_id, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir'), 'tab' => request('tab')]), 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir')]) }}" class="btn btn-danger flex-grow-1" target="_blank">
                            <i class="fas fa-file-pdf"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(!isset($selectedParameter) || !$selectedParameter)
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i> Silakan pilih Parameter Uji terlebih dahulu untuk menampilkan Inhouse Control.
        </div>
    @else
        
        <!-- Raw Data Logger (20 Pengulangan) -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table me-2"></i>Tabel Data Bias Test VM — 20 Pengulangan
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle" style="font-size: 0.75rem;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th rowspan="2" class="align-middle">Tanggal</th>
                                <th rowspan="2" class="align-middle">Pengujian Ke-</th>
                                <th rowspan="2" class="align-middle">Kode Sampel</th>
                                <th rowspan="2" class="align-middle" style="width:30px;"></th>
                                <th class="bg-primary text-white">M1</th>
                                <th class="bg-secondary text-white">M2</th>
                                <th class="bg-primary text-white">M2-M1</th>
                                <th class="bg-primary text-white">M3</th>
                                <th class="bg-secondary text-white">M2-M3</th>
                                <th rowspan="2" class="align-middle">LOSS%</th>
                                <th rowspan="2" class="align-middle bg-info text-white">IM</th>
                                <th rowspan="2" class="align-middle text-primary">VM%</th>
                                <th colspan="2" class="bg-warning text-dark">Absolute Difference</th>
                                <th rowspan="2" class="align-middle bg-info text-white">Avg %adb</th>
                                <th rowspan="2" class="align-middle bg-success text-white">Avg %db</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="bg-light text-muted small py-1">Manual (Biru) & Auto (Abu)</th>
                                <th class="bg-warning text-dark">Value</th>
                                <th class="bg-warning text-dark">Eval (< 1.00)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasilList as $idx => $hasil)
                                @php
                                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                    $totalReps = $dm['total_reps'] ?? 0;
                                    $totalPairs = $dm['total_pairs'] ?? 0;
                                    $imValue = $dm['IM'] ?? '-';
                                @endphp
                                
                                @for($r = 1; $r <= $totalReps; $r++)
                                    @php
                                        $M1 = $dm["M1_R{$r}"] ?? '-';
                                        $M2M1 = $dm["M2M1_R{$r}"] ?? '-';
                                        $M3 = $dm["M3_R{$r}"] ?? '-';
                                        $M2 = $dm["M2_R{$r}_Result"] ?? '-';
                                        $M2M3 = $dm["M2M3_R{$r}_Result"] ?? '-';
                                        $Loss = $dm["Loss_R{$r}_Result"] ?? '-';
                                        $VM = $dm["VM_R{$r}_Result"] ?? '-';
                                        
                                        $isOdd = ($r % 2 == 1);
                                        $pairIdx = intdiv($r - 1, 2) + 1;
                                        $absDiff = $dm["Pair{$pairIdx}_AbsDiff"] ?? '-';
                                        $pairEval = $dm["Pair{$pairIdx}_Eval"] ?? '-';
                                        $avgAdb = $dm["Pair{$pairIdx}_AvgAdb"] ?? '-';
                                        $avgDb = $dm["Pair{$pairIdx}_AvgDb"] ?? '-';
                                    @endphp
                                    <tr style="{{ $isOdd ? 'border-top: 2px solid #bbb;' : '' }}">
                                        @if($r == 1)
                                            <td rowspan="{{ $totalReps }}" class="align-middle fw-bold">{{ $hasil->created_at->format('d/m/Y') }}</td>
                                            <td rowspan="{{ $totalReps }}" class="align-middle fw-bold" style="writing-mode: vertical-lr; text-orientation: mixed; font-size: 0.7rem;">BIAS TEST (PLATINA)</td>
                                            <td rowspan="{{ $totalReps }}" class="align-middle">{{ $hasil->kegiatan->kode_sampel ?? '-' }}</td>
                                        @endif
                                        
                                        <td class="fw-bold {{ $isOdd ? 'text-primary' : 'text-secondary' }}">{{ $r }}</td>
                                        <td>{{ $M1 }}</td>
                                        <td class="bg-light">{{ is_numeric($M2) ? number_format($M2, 4) : $M2 }}</td>
                                        <td class="fw-bold">{{ $M2M1 }}</td>
                                        <td>{{ $M3 }}</td>
                                        <td class="bg-light">{{ is_numeric($M2M3) ? number_format($M2M3, 4) : $M2M3 }}</td>
                                        <td>{{ is_numeric($Loss) ? number_format($Loss, 4) : $Loss }}</td>
                                        
                                        @if($r == 1)
                                            <td rowspan="{{ $totalReps }}" class="align-middle fw-bold text-info">{{ is_numeric($imValue) ? number_format($imValue, 4) : $imValue }}</td>
                                        @endif
                                        
                                        <td class="fw-bold text-primary">{{ is_numeric($VM) ? number_format($VM, 4) : $VM }}</td>
                                        
                                        @if($isOdd && ($r + 1) <= $totalReps)
                                            <td rowspan="2" class="align-middle fw-bold">{{ is_numeric($absDiff) ? number_format($absDiff, 4) : $absDiff }}</td>
                                            <td rowspan="2" class="align-middle">
                                                @if($pairEval === 'YES')
                                                    <span class="badge bg-success">YES</span>
                                                @elseif($pairEval === 'NO')
                                                    <span class="badge bg-danger">NO</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td rowspan="2" class="align-middle fw-bold">{{ is_numeric($avgAdb) ? number_format($avgAdb, 4) : $avgAdb }}</td>
                                            <td rowspan="2" class="align-middle fw-bold text-success">{{ is_numeric($avgDb) ? number_format($avgDb, 2) : $avgDb }}</td>
                                        @elseif($isOdd && ($r + 1) > $totalReps)
                                            {{-- Pengulangan ganjil terakhir tanpa pasangan --}}
                                            <td class="text-muted">-</td>
                                            <td class="text-muted">-</td>
                                            <td class="text-muted">-</td>
                                            <td class="text-muted">-</td>
                                        @endif
                                    </tr>
                                @endfor
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center py-4">Belum ada data Bias Test VM.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Kesimpulan Statistik (T-Test) -->
        @foreach($hasilList as $hasil)
            @php
                $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                $tTest = $dm['t_test'] ?? null;
            @endphp
            @if($tTest)
                <div class="card shadow mb-4 border-left-{{ $tTest['conclusion'] === 'Tidak Ada Bias' ? 'success' : 'danger' }}">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold {{ $tTest['conclusion'] === 'Tidak Ada Bias' ? 'text-success' : 'text-danger' }}">
                            <i class="fas fa-calculator me-2"></i>Kesimpulan Statistik (T-Test) — {{ $hasil->kegiatan->kode_sampel ?? '' }} ({{ $hasil->created_at->format('d/m/Y') }})
                        </h6>
                    </div>
                    <div class="card-body">
                                      <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold bg-light">Jumlah Pasangan (n)</td>
                                            <td>{{ $tTest['n'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">Mean Average %db</td>
                                            <td>{{ number_format($tTest['mean'], 4) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">Standar Deviasi (SD)</td>
                                            <td>{{ number_format($tTest['sd'], 4) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">Reference Value</td>
                                            <td>{{ number_format($tTest['reference'], 4) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold bg-light">Derajat Kebebasan (df)</td>
                                            <td>{{ $tTest['df'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">Tingkat Kepercayaan (α)</td>
                                            <td>{{ $tTest['alpha'] }} (two-tailed)</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">t-hitung</td>
                                            <td class="fw-bold {{ $tTest['t_hitung'] < $tTest['t_tabel'] ? 'text-success' : 'text-danger' }}">{{ number_format($tTest['t_hitung'], 4) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">t-tabel</td>
                                            <td>{{ number_format($tTest['t_tabel'], 4) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3 p-3 rounded {{ $tTest['conclusion'] === 'Tidak Ada Bias' ? 'bg-success' : 'bg-danger' }} bg-opacity-10">
                            @if($tTest['conclusion'] === 'Tidak Ada Bias')
                                <h4 class="text-success mb-1">
                                    <i class="fas fa-check-circle me-2"></i>TIDAK ADA BIAS
                                </h4>
                                <p class="mb-0 text-muted">t-hitung ({{ number_format($tTest['t_hitung'], 4) }}) < t-tabel ({{ number_format($tTest['t_tabel'], 4) }}) → Metode pengujian tidak memiliki bias signifikan terhadap metode standar.</p>
                            @else
                                <h4 class="text-danger mb-1">
                                    <i class="fas fa-times-circle me-2"></i>ADA BIAS
                                </h4>
                                <p class="mb-0 text-muted">t-hitung ({{ number_format($tTest['t_hitung'], 4) }}) ≥ t-tabel ({{ number_format($tTest['t_tabel'], 4) }}) → Metode pengujian memiliki bias signifikan terhadap metode standar.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    @endif
</div>
