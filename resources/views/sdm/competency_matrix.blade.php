@extends('layouts.app')

@section('content')
<style>
    .matrix-header-form { align-items: stretch; }

    .custom-select {
        position: relative;
        width: 160px;
        font-size: 0.73rem;
    }
    .custom-select.sertifikasi { width: 220px; }

    .custom-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 4px 8px;
        font-size: 0.73rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .custom-select-trigger:after {
        content: "";
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6c757d;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .custom-select.open .custom-select-trigger {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }

    .custom-select-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
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
    .custom-select.open .custom-select-options {
        display: block;
    }
    .custom-select-options li {
        padding: 6px 10px;
        font-size: 0.73rem;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .custom-select-options li:hover {
        background-color: #f1f3f5;
    }
    .custom-select-options li.selected {
        background-color: #0d6efd;
        color: #fff;
    }

    @media (max-width: 576px) {
        .matrix-header-row { flex-direction: column; align-items: stretch !important; }
        .matrix-header-form { flex-direction: column; }
        .custom-select,
        .matrix-header-form .btn { width: 100% !important; }
        .matrix-back-btn { width: 100%; text-align: center; }
    }
</style>
<div class="container-fluid px-3 pt-1 pb-2" style="font-size: 0.78rem;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2 px-1 matrix-header-row">
        <div class="w-100">
            <h4 class="fw-bold mb-0">Competency Matrix</h4>
            <p class="text-muted mb-0" style="font-size: 0.72rem;">Lihat status personil untuk satu jenis sertifikasi dalam satu waktu.</p>
            <form method="GET" action="{{ route('sdm.competency-matrix') }}" id="filterForm" class="d-flex align-items-center flex-wrap gap-2 mt-2 matrix-header-form">

                <div class="custom-select" id="selectKategori">
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                    <button type="button" class="custom-select-trigger">
                        {{ $kategori && isset($kategoriOptions[$kategori]) ? $kategoriOptions[$kategori] : 'Semua Kategori' }}
                    </button>
                    <ul class="custom-select-options">
                        <li data-value="" class="{{ !$kategori ? 'selected' : '' }}">Semua Kategori</li>
                        @foreach($kategoriOptions as $value => $label)
                            <li data-value="{{ $value }}" class="{{ $kategori === $value ? 'selected' : '' }}">{{ $label }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="custom-select sertifikasi" id="selectSertifikasi">
                    <input type="hidden" name="sertifikasi" value="{{ $jenisSertifikasi }}">
                    <button type="button" class="custom-select-trigger">
                        {{ $jenisSertifikasi ?: 'Pilih Sertifikasi' }}
                    </button>
                    <ul class="custom-select-options">
                        <li data-value="" class="{{ !$jenisSertifikasi ? 'selected' : '' }}">Pilih Sertifikasi</li>
                        @foreach($jenisSertifikasiOptions as $item)
                            <li data-value="{{ $item }}" class="{{ $jenisSertifikasi === $item ? 'selected' : '' }}">{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                @if($jenisSertifikasi)
                    <a href="{{ route('sdm.competency-matrix.pdf', array_filter(['kategori' => $kategori, 'sertifikasi' => $jenisSertifikasi])) }}" class="btn btn-outline-danger btn-sm px-2.5 py-1 fw-semibold text-nowrap rounded-pill" style="font-size: 0.73rem;">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                    </a>
                @endif
            </form>
        </div>
        <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1 fw-semibold rounded-pill me-1 matrix-back-btn" style="font-size: 0.73rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Personil
        </a>
    </div>

    <div>
        @if(!$jenisSertifikasi)
            <div class="text-center py-4 text-muted" style="font-size: 0.75rem;">
                <i class="bi bi-funnel fs-4 d-block mb-1"></i>
                Pilih jenis sertifikasi untuk melihat status tiap personil.
            </div>
        @else
            <div class="text-center text-muted py-1 mb-2" style="font-size: 0.73rem;">
                Menampilkan status sertifikasi: <span class="fw-semibold text-dark">{{ $jenisSertifikasi }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0 bg-white shadow-sm rounded-3">
                    <thead>
                        <tr>
                            <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: left; vertical-align: middle; min-width: 200px;">Nama Personil</th>
                            <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle; width: 160px;">Status Sertifikasi</th>
                            <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle; width: 130px;">Berlaku Sampai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matrix as $row)
                            <tr>
                                <td class="text-start fw-bold" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">
                                    <a href="{{ route('sdm.kompetensi.detail', $row['personil']->personil_id) }}" class="text-decoration-none text-dark">
                                        {{ $row['personil']->nama }}
                                    </a>
                                    <div class="text-muted fw-normal" style="font-size: 0.65rem;">{{ $row['personil']->jabatan }}</div>
                                </td>
                                <td class="text-center" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">
                                    @if($row['kompetensi'])
                                        <a href="{{ route('sdm.kompetensi.detail', $row['personil']->personil_id) }}" class="text-decoration-none">
                                            <span class="badge {{ $row['status']['class'] }} px-2 py-1.5 text-nowrap" style="font-size: 0.65rem;"><i class="bi bi-{{ $row['status']['icon'] }} me-1"></i>{{ $row['status']['label'] }}</span>
                                        </a>
                                    @else
                                        <span class="badge bg-light text-dark border px-2 py-1.5 text-nowrap" style="font-size: 0.65rem;"><i class="bi bi-dash-circle me-1"></i>Belum Pernah</span>
                                    @endif
                                </td>
                                <td class="text-center" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">{{ $row['kompetensi']?->tanggal_berakhir?->format('d-m-Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4" style="font-size: 0.73rem;">
                                    Belum ada data personil aktif yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.custom-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.custom-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.custom-select-options li');
            const form = wrapper.closest('form');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.custom-select.open').forEach(function (other) {
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
                    form.submit();
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.custom-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });
    });
</script>
@endsection