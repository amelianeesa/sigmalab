@php
    $daftarPeralatan = $peralatanList ?? collect();
    $jumlahPeralatan = $daftarPeralatan->count();
    $jumlahBaris = max((int) ceil($jumlahPeralatan / 2), min($jumlahPeralatan, 5), 1);
@endphp

@if(!empty($ruangan))
    <style>
        .daftar-peralatan {
            max-height: 190px;
            overflow-y: auto;
            font-size: 0.72rem;
        }
        .daftar-peralatan .judul-peralatan,
        .daftar-peralatan .isi-peralatan {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .daftar-peralatan .judul-peralatan {
            position: sticky;
            top: 0;
            z-index: 1;
            background: linear-gradient(rgba(30, 136, 214, 0.15), rgba(30, 136, 214, 0.15)), #ffffff;
        }
        .daftar-peralatan .isi-peralatan {
            grid-auto-flow: column;
            grid-template-rows: repeat(var(--jumlah-baris), 32px);
        }
        .daftar-peralatan .baris-data {
            display: grid;
            grid-template-columns: 40px minmax(0, 1fr);
            align-items: center;
            column-gap: 6px;
            padding: 0 8px;
            border-bottom: 1px solid #e9ecef;
        }
        .daftar-peralatan .baris-data.ada-aksi {
            grid-template-columns: 40px minmax(0, 1fr) 70px;
        }
        .daftar-peralatan .judul-peralatan .baris-data {
            height: 30px;
            font-weight: 700;
        }
        .daftar-peralatan .isi-peralatan .baris-data:hover {
            background-color: #f8f9fa;
        }
        .daftar-peralatan .kolom-kiri {
            border-right: 1px solid #dee2e6;
        }
        html body .dashboard-container .daftar-peralatan button.btn.btn-aksi-peralatan {
            width: 28px !important;
            min-width: 28px !important;
            height: 26px !important;
            min-height: 26px !important;
            padding: 0 !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .daftar-peralatan .judul-peralatan,
            .daftar-peralatan .isi-peralatan {
                grid-template-columns: minmax(0, 1fr);
            }
            .daftar-peralatan .isi-peralatan {
                grid-auto-flow: row;
                grid-template-rows: none;
                grid-auto-rows: 32px;
            }
            .daftar-peralatan .judul-peralatan .baris-data + .baris-data {
                display: none;
            }
            .daftar-peralatan .kolom-kiri {
                border-right: 0;
            }
        }
    </style>
    <div class="border rounded bg-white mb-2">
        <div class="d-flex justify-content-between align-items-center px-2 py-1 bg-light border-bottom gap-2">
            <span class="fw-bold text-dark" style="font-size: 0.75rem;">
                <i class="fas fa-tools text-primary me-1"></i> PERALATAN DI RUANGAN: {{ $ruangan }}
                <span class="badge bg-primary ms-1" style="font-size: 10px;">{{ $daftarPeralatan->count() }} Alat</span>
            </span>
            @if($canManage)
                <button type="button" class="btn text-white btn-sm py-1 px-2 shadow-sm" style="font-size: 0.7rem; background-color: var(--sl-primary);" data-bs-toggle="modal" data-bs-target="#tambahPeralatanModal">
                    <i class="fas fa-plus me-1"></i> Tambah Peralatan
                </button>
            @endif
        </div>

        @if($errors->peralatan->any())
            <div class="alert alert-danger py-1 px-2 m-2" style="font-size: 0.72rem;">
                {{ $errors->peralatan->first() }}
            </div>
        @endif

        <div class="daftar-peralatan">
            @if($jumlahPeralatan > 0)
                <div class="judul-peralatan">
                    @for($kolom = 1; $kolom <= 2; $kolom++)
                        <div class="baris-data {{ $canManage ? 'ada-aksi' : '' }} {{ $kolom == 1 ? 'kolom-kiri' : '' }}">
                            @if($kolom == 1 || $jumlahPeralatan > $jumlahBaris)
                                <span class="text-center">No</span>
                                <span>Nama Peralatan</span>
                                @if($canManage)
                                    <span class="text-center">Aksi</span>
                                @endif
                            @endif
                        </div>
                    @endfor
                </div>
                <div class="isi-peralatan" style="--jumlah-baris: {{ $jumlahBaris }};">
                    @foreach($daftarPeralatan as $peralatan)
                        <div class="baris-data {{ $canManage ? 'ada-aksi' : '' }} {{ $loop->index < $jumlahBaris ? 'kolom-kiri' : '' }}">
                            <span class="text-center">{{ $loop->iteration }}</span>
                            <span class="text-truncate" title="{{ $peralatan->nama_peralatan }}">{{ $peralatan->nama_peralatan }}</span>
                            @if($canManage)
                                <span class="text-center text-nowrap">
                                    <button type="button" class="btn btn-warning btn-sm text-dark btn-aksi-peralatan" style="font-size: 10px;" data-bs-toggle="modal" data-bs-target="#editPeralatanModal-{{ $peralatan->peralatan_ruangan_id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('inventori.monitoring.destroyPeralatan', $peralatan->peralatan_ruangan_id) }}" method="POST" class="d-inline form-hapus-peralatan" data-nama="{{ $peralatan->nama_peralatan }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-aksi-peralatan" style="font-size: 10px;" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-2">Belum ada peralatan di ruangan ini.</div>
            @endif
        </div>
    </div>

    @if($canManage)
        <div class="modal fade" id="tambahPeralatanModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header py-1 px-3 text-white" style="background-color: var(--sl-primary);">
                        <h6 class="modal-title fw-bold" style="font-size: 0.85rem;">Tambah Peralatan - {{ $ruangan }}</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <form action="{{ route('inventori.monitoring.storePeralatan') }}" method="POST" id="formTambahPeralatan">
                            @csrf
                            <input type="hidden" name="nama_ruangan" value="{{ $ruangan }}">
                            <label class="form-label small fw-bold">Nama Peralatan</label>
                            <div id="daftarInputPeralatan">
                                <div class="input-group input-group-sm mb-2 baris-peralatan">
                                    <input type="text" name="nama_peralatan[]" class="form-control" maxlength="150" autocomplete="off" required>
                                    <button type="button" class="btn btn-outline-danger btn-hapus-input-peralatan" title="Hapus Baris"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button type="button" id="btnTambahInputPeralatan" class="btn btn-outline-secondary btn-sm w-100 fw-bold py-1" style="font-size: 0.7rem;">
                                <i class="fas fa-plus me-1"></i> Tambah Baris
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer py-1 px-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="formTambahPeralatan" class="btn text-white btn-sm shadow-sm" style="background-color: var(--sl-primary);">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        @foreach($daftarPeralatan as $peralatan)
            <div class="modal fade" id="editPeralatanModal-{{ $peralatan->peralatan_ruangan_id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('inventori.monitoring.updatePeralatan', $peralatan->peralatan_ruangan_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header py-1 px-3 text-white" style="background-color: var(--sl-primary);">
                                <h6 class="modal-title fw-bold" style="font-size: 0.85rem;">Edit Peralatan</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-3">
                                <label class="form-label small fw-bold">Nama Peralatan</label>
                                <input type="text" name="nama_peralatan" class="form-control form-control-sm" value="{{ $peralatan->nama_peralatan }}" maxlength="150" autocomplete="off" required>
                            </div>
                            <div class="modal-footer py-1 px-3">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn text-white btn-sm shadow-sm" style="background-color: var(--sl-primary);">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <hr class="my-2">
@endif

@if(!empty($ruangan) && $canManage)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const daftarInput = document.getElementById('daftarInputPeralatan');

        document.getElementById('btnTambahInputPeralatan').addEventListener('click', function () {
            const baris = daftarInput.querySelector('.baris-peralatan').cloneNode(true);
            const input = baris.querySelector('input');
            input.value = '';
            input.required = false;
            daftarInput.appendChild(baris);
            input.focus();
        });

        daftarInput.addEventListener('click', function (event) {
            const tombol = event.target.closest('.btn-hapus-input-peralatan');
            if (!tombol) return;

            const semuaBaris = daftarInput.querySelectorAll('.baris-peralatan');
            if (semuaBaris.length === 1) {
                semuaBaris[0].querySelector('input').value = '';
                return;
            }

            tombol.closest('.baris-peralatan').remove();
            daftarInput.querySelector('input').required = true;
        });

        document.querySelectorAll('.form-hapus-peralatan').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Hapus peralatan "' + form.dataset.nama + '"?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
@endpush
@endif