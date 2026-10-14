<!-- MODAL PANDUAN KHUSUS LEMBAR KERJA UJI BANDING (D-PRINT-NONE) -->
<div class="modal fade d-print-none" id="modalRumus-{{ $pid }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: var(--sl-primary);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Panduan Lembar Kerja Uji Banding: {{ $code }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">
                    Penjelasan pengisian kolom Lembar Kerja Sampel Uji Banding (Profisiensi) untuk parameter <strong>{{ $code }}</strong>:
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
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
                                <tr><td><strong>Ref No, Time, Furnace</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Informasi instrumen & kondisi oven.</td></tr>
                                <tr><td><strong>M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan kosong.</td></tr>
                                <tr class="table-info"><td><strong>M2</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M1 + A</code></td></tr>
                                <tr class="table-info"><td><strong>M3</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M1 + B</code></td></tr>
                                <tr><td><strong>A</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa sampel awal.</td></tr>
                                <tr><td><strong>B</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa residu kering.</td></tr>
                                <tr class="table-warning"><td><strong>M%</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">% adb</td><td><code>((A - B) / A) × 100</code></td></tr>

                            @elseif($code === 'ASH')
                                <tr><td><strong>M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan kosong.</td></tr>
                                <tr><td><strong>M2-M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa sampel batubara yang ditimbang langsung.</td></tr>
                                <tr class="table-info"><td><strong>M2</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M1 + (M2-M1)</code></td></tr>
                                <tr><td><strong>M3-M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa abu hasil pembakaran.</td></tr>
                                <tr class="table-info"><td><strong>M3</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">gram</td><td><code>M1 + (M3-M1)</code></td></tr>
                                <tr class="table-warning"><td><strong>ASH%</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">% adb</td><td><code>((M3 - M1) / (M2 - M1)) × 100</code></td></tr>

                            @elseif($code === 'VM')
                                <tr><td><strong>M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat krus kosong.</td></tr>
                                <tr><td><strong>M2-M1</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa sampel batubara awal.</td></tr>
                                <tr><td><strong>M2-M3</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Massa yang menguap (loss).</td></tr>
                                <tr class="table-info"><td><strong>%LOSS</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td><code>((M2-M3) / (M2-M1)) × 100</code></td></tr>
                                <tr class="table-info"><td><strong>%M adb</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td>Diambil dari nilai Moisture (IM).</td></tr>
                                <tr class="table-warning"><td><strong>%VM</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">% adb</td><td><code>%LOSS - %M adb</code> (atau input override manual)</td></tr>

                            @elseif(in_array($code, ['CV', 'GCV']))
                                <tr><td><strong>Weight of Crucible</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat cawan pembakaran bom.</td></tr>
                                <tr><td><strong>Sample Mass</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">gram</td><td>Berat sampel uji.</td></tr>
                                <tr><td><strong>Preliminary Result</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">cal/g</td><td>Hasil kasar pembacaan kalorimeter.</td></tr>
                                <tr><td><strong>Titrant & Fuse</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Koreksi asam titrasi (e1) & kawat pembakar (e2).</td></tr>
                                <tr class="table-info"><td><strong>Total Sulfur (e3)</strong></td><td class="text-center"><span class="badge bg-primary">Otomatis</span></td><td class="text-center">%</td><td>Koreksi sulfur dari parameter Total Sulfur.</td></tr>
                                <tr class="table-warning"><td><strong>Final Result</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">cal/g</td><td>Kalori bersih setelah dikurangi faktor koreksi (e1+e2+e3+e4).</td></tr>

                            @elseif($code === 'CHN')
                                <tr><td><strong>Weight</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">mg</td><td>Berat sampel CHN analyzer.</td></tr>
                                <tr class="table-warning"><td><strong>%C, %H, %N</strong></td><td class="text-center"><span class="badge bg-success">Hasil Uji</span></td><td class="text-center">% db</td><td>Persentase unsur Carbon, Hydrogen, Nitrogen basis kering.</td></tr>

                            @else
                                <tr><td><strong>Hasil Uji (adb)</strong></td><td class="text-center"><span class="badge bg-secondary">Manual</span></td><td class="text-center">-</td><td>Nilai hasil pengujian analis.</td></tr>
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