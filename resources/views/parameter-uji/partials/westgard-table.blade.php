@if(($jenisGrafik ?? 'in_house') === 'in_house')
            <style>
                .wg-head th {
                    background-color: #1b3152 !important; color: #ffffff !important; border-color: #ffffff !important;
                    font-size: 0.72rem !important; vertical-align: middle !important;
                }
                .wg-table td { font-size: 0.75rem !important; vertical-align: middle !important; }
                .wg-action-btn {
                    width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;
                    font-size: 0.72rem; padding: 0 !important; border-radius: 6px;
                }

                @media (max-width: 767.98px) {
                    .wg-scroll { max-height: 480px !important; }
                    .wg-table thead { display: none; }
                    .wg-table, .wg-table tbody, .wg-table tr, .wg-table td { display: block; width: 100%; }
                    .wg-table tbody tr {
                        display: grid; grid-template-columns: repeat(6, 1fr); gap: 2px 4px;
                        border: 1px solid #dee2e6; border-radius: 8px; padding: 8px 10px; margin: 8px;
                        width: auto; background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
                    }
                    .wg-table td { border: 0 !important; padding: 2px 0 !important; background: transparent !important; text-align: center; }
                    .wg-table td.wg-date   { grid-column: 1 / 5; order: 1; text-align: left; font-size: 0.8rem !important; }
                    .wg-table td.wg-action { grid-column: 5 / 7; order: 2; text-align: right; }
                    .wg-table td.wg-rule   { grid-column: span 2; order: 3; }
                    .wg-table td.wg-rule::before {
                        content: attr(data-label); display: block; font-size: 0.62rem; font-weight: 600; color: #1b3152;
                    }
                    .wg-table td.wg-status { grid-column: 1 / -1; order: 4; text-align: left; border-top: 1px solid #eee !important; margin-top: 4px; padding-top: 6px !important; }
                    .wg-table td.wg-status::before { content: 'Terima / Tolak: '; font-weight: 600; color: #1b3152; }
                    .wg-table td.wg-alasan { grid-column: 1 / -1; order: 5; text-align: left; }
                    .wg-table td.wg-alasan::before { content: 'Alasan: '; font-weight: 600; color: #1b3152; }
                }
            </style>

            <!-- Tabel Evaluasi Control Chart (Westgard) -->
            <div class="card shadow-sm mb-3 border-0">
                <div class="card-header py-2" style="background-color: #1b3152; color: #ffffff; font-weight: 600; font-size: 0.85rem;">
                    Tabel Evaluasi Control Chart (Westgard Rules)
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive wg-scroll" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-striped mb-0 text-center align-middle wg-table">
                            <thead class="wg-head sticky-top">
                                <tr>
                                    <th rowspan="2">Tanggal</th>
                                    <th colspan="6">Evaluasi Aturan Westgard</th>
                                    <th rowspan="2">Terima / Tolak</th>
                                    <th rowspan="2">Alasan</th>
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <th>1 (2s)</th>
                                    <th>1 (3s)</th>
                                    <th>2 (2s)</th>
                                    <th>R (4s)</th>
                                    <th>4 (1s)</th>
                                    <th>10x</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $validIdx = 0; @endphp
                                @foreach($hasilList as $hasil)
                                    @if($hasil->status_berketerimaan !== 'gagal_duplo' && $hasil->status_berketerimaan !== 'pending')
                                        @php
                                            $eval = $chartData['evaluations'][$validIdx] ?? null;
                                            $validIdx++;
                                        @endphp
                                        @if($eval)
                                        <tr>
                                            <td class="fw-bold wg-date">{{ $hasil->created_at->format('d/m/Y') }}</td>
                                            <td class="wg-rule {{ $eval['1_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}" data-label="1 (2s)">{{ $eval['1_2s'] }}</td>
                                            <td class="wg-rule {{ $eval['1_3s'] == 'Yes' ? 'text-danger fw-bold' : '' }}" data-label="1 (3s)">{{ $eval['1_3s'] }}</td>
                                            <td class="wg-rule {{ $eval['2_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}" data-label="2 (2s)">{{ $eval['2_2s'] }}</td>
                                            <td class="wg-rule {{ $eval['R_4s'] == 'Yes' ? 'text-danger fw-bold' : '' }}" data-label="R (4s)">{{ $eval['R_4s'] }}</td>
                                            <td class="wg-rule {{ $eval['4_1s'] == 'Yes' ? 'text-danger fw-bold' : '' }}" data-label="4 (1s)">{{ $eval['4_1s'] }}</td>
                                            <td class="wg-rule {{ $eval['10x']  == 'Yes' ? 'text-danger fw-bold' : '' }}" data-label="10x">{{ $eval['10x'] }}</td>
                                            <td class="fw-bold wg-status {{ $eval['status'] == 'Tolak' ? 'text-danger' : 'text-success' }}">
                                                {{ $eval['status'] }}
                                            </td>
                                            <td class="text-start wg-alasan {{ $eval['status'] == 'Tolak' ? 'text-danger' : '' }} {{ $eval['alasan'] != '-' ? '' : 'd-none d-md-table-cell' }}">{{ $eval['alasan'] != '-' ? $eval['alasan'] : '' }}</td>
                                            <td class="wg-action">
                                                <button type="button" class="btn btn-warning btn-sm wg-action-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#modalOverrideGlobal" 
                                                    data-id="{{ $eval['hasil_uji_id'] }}"
                                                    data-status="{{ $eval['status'] }}"
                                                    data-alasan="{{ $eval['alasan'] }}"
                                                    title="Edit Evaluasi" aria-label="Edit Evaluasi">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

@endif