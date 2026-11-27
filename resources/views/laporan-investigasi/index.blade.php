@extends('layouts.app')

@section('title', 'Laporan Investigasi (CAPA) - Verifikasi Mutu')

@section('content')
<div class="container-fluid py-3">
    <div class="container-fluid py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2" style="font-size: 0.78rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('verifikasi-mutu.index') }}" class="text-decoration-none">Verifikasi Mutu</a>
            </li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
                Laporan Investigasi
            </li>
        </ol>
    </nav>
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-0 text-primary"><i class="fas fa-clipboard-list me-2"></i>Laporan Investigasi (CAPA) Outlier</h4>
            <small class="text-muted">Pusat pemantauan catatan investigasi dan ketidaksesuaian dari seluruh modul QC.</small>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body py-2 px-3">
                    <div class="text-muted small fw-semibold">Total Kasus Outlier</div>
                    <div class="fs-4 fw-bold text-primary">{{ $totalOutlier }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-danger">
                <div class="card-body py-2 px-3">
                    <div class="text-muted small fw-semibold">Menunggu Investigasi</div>
                    <div class="fs-4 fw-bold text-danger">{{ $menungguCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-success">
                <div class="card-body py-2 px-3">
                    <div class="text-muted small fw-semibold">Selesai Investigasi (CAPA)</div>
                    <div class="fs-4 fw-bold text-success">{{ $selesaiCount }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <h6 class="fw-bold mb-0"><i class="fas fa-list me-2 text-primary"></i>Daftar Catatan Investigasi</h6>
            <div class="d-flex flex-wrap gap-2">
                <select id="filterJenisQc" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Semua Jenis QC</option>
                    <option value="QC In-House">QC In-House (Homogenitas / Stabilitas)</option>
                    <option value="QC Harian In-House">QC Harian In-House</option>
                    <option value="QC CRM Harian">QC CRM Harian</option>
                    <option value="Verifikasi Teknis CRM">Verifikasi Teknis CRM</option>
                    <option value="QC Uji Banding">QC Uji Banding</option>
                </select>
                <select id="filterStatusInv" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Investigasi">Menunggu Investigasi</option>
                    <option value="Selesai">Selesai Investigasi</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableInvestigasi" class="table table-hover table-bordered align-middle table-sm w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis QC</th>
                            <th>Sampel / Batch / Lot</th>
                            <th>Parameter</th>
                            <th>Nilai Uji</th>
                            <th>Acuan / Keterangan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanList as $item)
                        <tr>
                            <td class="text-nowrap">{{ $item['tanggal_fmt'] }}</td>
                            <td><span class="badge {{ $item['badge_qc'] }}">{{ $item['jenis_qc'] }}</span></td>
                            <td class="fw-semibold">{{ $item['kode_sampel'] }}</td>
                            <td class="fw-bold">{{ $item['parameter'] }}</td>
                            <td class="text-danger fw-bold">{{ $item['nilai_uji'] }}</td>
                            <td class="small text-muted">{{ $item['nilai_acuan'] }}</td>
                            <td>
                                @if($item['status_investigasi'] === 'selesai_investigasi')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i> Menunggu Investigasi</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                @if($item['status_investigasi'] === 'selesai_investigasi')
                                    <button type="button" class="btn btn-sm btn-success btn-baca-capa py-0 px-2"
                                        data-tanggal="{{ $item['tanggal_fmt'] }}"
                                        data-jenis="{{ $item['jenis_qc'] }}"
                                        data-sampel="{{ $item['kode_sampel'] }}"
                                        data-param="{{ $item['parameter'] }}"
                                        data-akar="{{ $item['akar_masalah'] }}"
                                        data-temuan="{{ $item['catatan_investigasi'] }}"
                                        data-perbaikan="{{ $item['tindakan_perbaikan'] }}"
                                        data-pdf="{{ $item['url_pdf'] ?? '' }}">
                                        <i class="fas fa-file-alt me-1"></i> Baca CAPA
                                    </button>
                                    @if($item['url_pdf'])
                                        <a href="{{ $item['url_pdf'] }}" target="_blank" class="btn btn-sm btn-outline-danger py-0 px-2 ms-1" title="Download PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ $item['url_isi'] }}" class="btn btn-sm btn-danger py-0 px-2">
                                        <i class="fas fa-search me-1"></i> Isi Investigasi
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Baca CAPA --}}
<div class="modal fade" id="modalBacaCapa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-medical-alt me-2"></i>Detail Laporan Investigasi (CAPA)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 small bg-light p-2 rounded">
                    <div class="col-md-3"><strong>Tanggal:</strong> <span id="m-tanggal"></span></div>
                    <div class="col-md-3"><strong>Jenis QC:</strong> <span id="m-jenis"></span></div>
                    <div class="col-md-3"><strong>Sampel:</strong> <span id="m-sampel"></span></div>
                    <div class="col-md-3"><strong>Parameter:</strong> <span id="m-param"></span></div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-danger small">Akar Masalah (Root Cause):</label>
                    <div class="p-2 border rounded bg-white" id="m-akar"></div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-dark small">Catatan Temuan Investigasi:</label>
                    <div class="p-2 border rounded bg-white" id="m-temuan"></div>
                </div>
                <div class="mb-2">
                    <label class="fw-bold text-success small">Tindakan Perbaikan (Corrective Action):</label>
                    <div class="p-2 border rounded bg-white" id="m-perbaikan"></div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <a href="#" id="m-pdf-btn" target="_blank" class="btn btn-danger btn-sm d-none"><i class="fas fa-file-pdf me-1"></i> Download PDF</a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $.fn.dataTable.ext.search.push(function(settings, data) {
        if (settings.nTable.id !== 'tableInvestigasi') return true;
        let fJenis = $('#filterJenisQc').val();
        let fStatus = $('#filterStatusInv').val();
        let jenisStr = data[1] || "";
        let statusStr = data[6] || "";

        if (fJenis && !jenisStr.includes(fJenis)) return false;
        if (fStatus && !statusStr.includes(fStatus)) return false;
        return true;
    });

    let tbl = $('#tableInvestigasi').DataTable({
        order: [],
        pageLength: 15,
        language: { search: "Cari:", zeroRecords: "Tidak ada data investigasi yang cocok." }
    });

    $('#filterJenisQc, #filterStatusInv').on('change', function() {
        tbl.draw();
    });

    $(document).on('click', '.btn-baca-capa', function() {
        $('#m-tanggal').text($(this).data('tanggal'));
        $('#m-jenis').text($(this).data('jenis'));
        $('#m-sampel').text($(this).data('sampel'));
        $('#m-param').text($(this).data('param'));
        $('#m-akar').text($(this).data('akar'));
        $('#m-temuan').text($(this).data('temuan'));
        $('#m-perbaikan').text($(this).data('perbaikan'));

        let pdf = $(this).data('pdf');
        if (pdf) {
            $('#m-pdf-btn').attr('href', pdf).removeClass('d-none');
        } else {
            $('#m-pdf-btn').addClass('d-none');
        }
        new bootstrap.Modal(document.getElementById('modalBacaCapa')).show();
    });
});
</script>
@endpush
@endsection