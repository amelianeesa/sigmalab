<!-- MODAL PANDUAN IN-HOUSE & QC CRM (D-PRINT-NONE) -->
<div class="modal fade d-print-none" id="modalRumus-{{ $pid }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: var(--sl-primary);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-calculator me-2"></i>Panduan Alur & Rumus: {{ $code }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">
                    Penjelasan sifat kolom (input manual vs kalkulasi otomatis) dan rumus perhitungan standar ASTM pada pengujian <strong>{{ $code }}</strong>:
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Nama Kolom</th>
                                <th width="15%" class="text-center">Sifat Kolom</th>
                                <th width="15%" class="text-center">Satuan</th>
                                <th width="50%">Rumus / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(in_array($code, ['IM', 'RM']))
                                <tr><td><strong>DISH NO.</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Nomor cawan timbang.</td></tr>
                                <tr><td><strong>M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan kosong.</td></tr>
                                <tr><td><strong>A</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat sampel batubara awal.</td></tr>
                                <tr><td><strong>M2</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M1 + A</code> (Berat cawan + sampel basah)</td></tr>
                                <tr><td><strong>M3</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan + sampel setelah dioven.</td></tr>
                                <tr><td><strong>B</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M3 - M1</code> (Massa sampel kering setelah dioven)</td></tr>
                                <tr><td><strong>M%</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">% adb</td><td><code>((A - B) / A) × 100</code> (Kadar Air Inherent)</td></tr>
                                <tr><td><strong>Abs. Diff</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td><code>|Duplo 1 - Duplo 2|</code></td></tr>
                                <tr><td><strong>Average %</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">% adb</td><td><code>(Duplo 1 + Duplo 2) / 2</code></td></tr>

                            @elseif($code === 'ASH')
                                <tr><td><strong>DISH NO.</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Nomor cawan timbang.</td></tr>
                                <tr><td><strong>M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan kosong.</td></tr>
                                <tr><td><strong>M2 - M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa sampel batubara awal (ditimbang langsung).</td></tr>
                                <tr><td><strong>M2</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M1 + (M2 - M1)</code> (Berat cawan + sampel)</td></tr>
                                <tr><td><strong>M3</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan + sisa abu paska pembakaran tanur.</td></tr>
                                <tr><td><strong>M3 - M1</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M3 - M1</code> (Massa abu murni)</td></tr>
                                <tr><td><strong>ASH%</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">% adb</td><td><code>((M3 - M1) / (M2 - M1)) × 100</code></td></tr>
                                <tr><td><strong>Abs. Diff</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td><code>|ASH Duplo 1 - ASH Duplo 2|</code></td></tr>
                                <tr><td><strong>AVERAGE %adb</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">% adb</td><td><code>(ASH Duplo 1 + ASH Duplo 2) / 2</code></td></tr>
                                <tr><td><strong>%db (Dry Basis)</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">% db</td><td><code>ASH(adb) × (100 / (100 - IM))</code></td></tr>

                            @elseif($code === 'VM')
                                <tr><td><strong>DISH NO.</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Nomor krus porselen timbang.</td></tr>
                                <tr><td><strong>M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat krus kosong + tutup.</td></tr>
                                <tr><td><strong>M2 - M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa sampel batubara awal (ditimbang langsung).</td></tr>
                                <tr><td><strong>M2</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M1 + (M2 - M1)</code> (Berat krus + sampel)</td></tr>
                                <tr><td><strong>M3</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat krus + sampel setelah pemanasan 900°C.</td></tr>
                                <tr><td><strong>M2 - M3</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M2 - M3</code> (Massa zat terbang yang hilang)</td></tr>
                                <tr><td><strong>LOSS%</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td><code>((M2 - M3) / (M2 - M1)) × 100</code></td></tr>
                                <tr><td><strong>IM</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td>Otomatis ditarik dari nilai rata-rata Tab <strong>IM</strong>.</td></tr>
                                <tr><td><strong>VM%</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">% adb</td><td><code>LOSS% - IM</code></td></tr>
                                <tr><td><strong>Abs. Diff</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td><code>|VM Duplo 1 - VM Duplo 2|</code></td></tr>
                                <tr><td><strong>AVERAGE %adb</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">% adb</td><td><code>(VM Duplo 1 + VM Duplo 2) / 2</code></td></tr>
                                <tr><td><strong>%db (Dry Basis)</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">% db</td><td><code>VM(adb) × (100 / (100 - IM))</code></td></tr>

                            @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                <tr><td><strong>DISH NO.</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Nomor perahu pembakaran sulfur.</td></tr>
                                <tr><td><strong>Massa sample</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa sampel batubara yang ditimbang.</td></tr>
                                <tr><td><strong>TS (Adb)</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">% adb</td><td>Hasil pembacaan instrumen Sulfur Analyzer per baris.</td></tr>
                                <tr><td><strong>Average % (adb)</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">% adb</td><td><code>(TS Simplo + TS Duplo) / 2</code> (Rata-rata kadar sulfur adb)</td></tr>
                                <tr><td><strong>Average % (db)</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">% db</td><td><code>(100 / (100 - IM Average)) × Average % (adb)</code> (Basis Kering)</td></tr>

                            @elseif(in_array($code, ['CV', 'GCV']))
                                <tr><td><strong>Vessel No</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Nomor tabung bejana bom kalorimeter (Bomb Vessel).</td></tr>
                                <tr><td><strong>Call ID</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Kode identifikasi kalibrasi / running alat.</td></tr>
                                <tr><td><strong>Weight of Crucible</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan pembakaran bom kosong.</td></tr>
                                <tr><td><strong>Sample Mass</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa sampel batubara yang ditimbang ke dalam cawan.</td></tr>
                                <tr><td><strong>Preliminary Result (PR)</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">cal/g</td><td>Hasil pembacaan awal dari display alat kalorimeter.</td></tr>
                                <tr><td><strong>Ee</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">cal/°C</td><td>Kapasitas panas efektif kalorimeter (Energy equivalent).</td></tr>
                                <tr><td><strong>t</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">°C</td><td><code>(Preliminary Result / Ee) × Sample Mass</code> (Kenaikan suhu terkoreksi)</td></tr>
                                <tr><td><strong>Volume of Titrant (VT)</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">ml</td><td>Volume titran pencucian bom (koreksi asam nitrat).</td></tr>
                                <tr><td><strong>Length of Fuse (LF)</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">cm</td><td>Panjang kawat pemantik yang terbakar.</td></tr>
                                <tr><td><strong>Total TS</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td>Otomatis ditarik dari nilai parameter <strong>Total Sulfur (TS adb)</strong>.</td></tr>
                                <tr><td><strong>Final Result (adb)</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">cal/g</td><td><code>(PR - (14.3 × 0.0699 × VT) - (2.3 × LF) - (13.2 × TS × SM)) / SM</code></td></tr>
                                <tr><td><strong>Average Result (adb)</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">cal/g</td><td><code>(Final Result Simplo + Final Result Duplo) / 2</code></td></tr>
                                <tr><td><strong>Average Result (db)</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">cal/g db</td><td><code>(100 / (100 - IM Average)) × Average Result (adb)</code> (Basis Kering)</td></tr>

                            @else
                                <tr><td><strong>Hasil Uji (adb)</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Nilai pembacaan analis / instrumen.</td></tr>
                                <tr><td><strong>Average %</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">-</td><td><code>(Duplo 1 + Duplo 2) / 2</code></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>