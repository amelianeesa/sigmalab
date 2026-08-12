@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Data belum dapat disimpan. Periksa isian berikut.
            <ul class="mb-0 mt-2 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 20px; flex-shrink: 0;">
                    {{ strtoupper(substr($personil->nama, 0, 2)) }}
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-1">{{ $personil->nama }}</h3>
                    <p class="text-muted mb-0">{{ $personil->jabatan }} — Unit Kerja: {{ $personil->unit_kerja }} | No. Pegawai: {{ $personil->no_induk }}</p>
                </div>
            </div>
            <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-1">Training Data Record</h5>
                <p class="text-muted small mb-0">Seluruh riwayat sertifikasi &amp; pelatihan yang pernah diikuti personil ini.</p>
            </div>
            @if(Auth::user()->role->nama_role !== 'Admin Lab')
            <button type="button" class="btn btn-dark btn-sm px-3 fw-semibold rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahSertifikasi">
                <i class="bi bi-plus-lg me-1"></i> Tambah Sertifikasi / Pelatihan
            </button>
            @endif
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3">Jenis Sertifikasi / Pelatihan</th>
                            <th class="py-3">No. Sertifikat</th>
                            <th class="py-3">Tanggal Terbit</th>
                            <th class="py-3">Masa Berlaku Berakhir</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3">Dokumen</th>
                            @if(Auth::user()->role->nama_role !== 'Admin Lab')
                            <th class="py-3 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil->kompetensi ?? [] as $komp)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $komp->jenis_sertifikasi }}</td>
                            <td><code>{{ $komp->no_sertifikasi ?? '-' }}</code></td>
                            <td>{{ $komp->tanggal_terbit?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $komp->tanggal_berakhir?->format('d-m-Y') ?? 'Tidak Terbatas' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $komp->status['class'] }} px-3 py-2 rounded-pill">
                                    <i class="bi bi-{{ $komp->status['icon'] }} me-1"></i>{{ $komp->status['label'] }}
                                </span>
                            </td>
                            <td>
                                @if($komp->file_sertifikat)
                                    <a href="{{ route('sdm.kompetensi.file', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm text-nowrap">
                                        <i class="bi bi-file-earmark-text me-1"></i> Lihat Dokumen
                                    </a>
                                @elseif(Auth::user()->role->nama_role !== 'Admin Lab')
                                    <form action="{{ route('sdm.kompetensi.file.upload', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-nowrap gap-2 align-items-center">
                                        @csrf
                                        <input type="file" name="file_sertifikat" class="form-control form-control-sm" accept="image/*,application/pdf" required style="min-width: 160px;">
                                        <button type="submit" class="btn btn-sm btn-dark text-nowrap">Unggah</button>
                                    </form>
                                @else
                                    <span class="text-muted small">Belum ada</span>
                                @endif
                            </td>
                            @if(Auth::user()->role->nama_role !== 'Admin Lab')
                            <td class="text-center text-nowrap">
                                <button type="button" class="btn btn-warning btn-sm" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#modalEditSertifikasi"
                                    data-action="{{ route('sdm.kompetensi.update', [$personil->personil_id, $komp->kompetensi_personil_id]) }}"
                                    data-parameter="{{ $komp->parameter_uji_id }}"
                                    data-jenis="{{ $komp->jenis_sertifikasi }}"
                                    data-no="{{ $komp->no_sertifikasi }}"
                                    data-terbit="{{ $komp->tanggal_terbit?->format('Y-m-d') }}"
                                    data-berakhir="{{ $komp->tanggal_berakhir?->format('Y-m-d') }}"
                                    data-has-file="{{ $komp->file_sertifikat ? '1' : '0' }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('sdm.kompetensi.destroy', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus riwayat sertifikasi &quot;{{ $komp->jenis_sertifikasi }}&quot;? Dokumen terkait juga akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-exclamation fs-2 d-block mb-2"></i>
                                Belum ada riwayat sertifikasi / pelatihan yang tercatat untuk personil ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->role->nama_role !== 'Admin Lab')
<div class="modal fade" id="modalTambahSertifikasi" tabindex="-1" aria-labelledby="modalTambahSertifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('sdm.kompetensi.store', $personil->personil_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6" id="modalTambahSertifikasiLabel"><i class="fas fa-certificate me-2"></i>Tambah Sertifikasi / Pelatihan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Terkait Parameter Uji <span class="text-muted fw-normal">(Opsional)</span></label>
                        <select name="parameter_uji_id" class="form-select form-select-sm" onchange="autoFillJenisSertifikasi(this, 'tambahJenisSertifikasi')">
                            <option value="">-- Pelatihan Umum / Non-Parameter --</option>
                            @foreach($parameterList as $param)
                                <option value="{{ $param->parameter_uji_id }}">{{ $param->nama_parameter }}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Pilih jika sertifikasi ini memberikan kewenangan pengujian parameter tertentu.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jenis Sertifikasi / Pelatihan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_sertifikasi" id="tambahJenisSertifikasi" class="form-control form-control-sm" placeholder="mis. Pelatihan K3 Laboratorium" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Sertifikat</label>
                        <input type="text" name="no_sertifikasi" class="form-control form-control-sm" placeholder="mis. K3-LAB/2026/001">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" class="form-control form-control-sm">
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Kosongkan bila tidak terbatas.</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Dokumen Sertifikat</label>
                        <input type="file" name="file_sertifikat" class="form-control form-control-sm" accept="image/*,application/pdf">
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Format: JPG, PNG, PDF (Maks. 2MB). Boleh dikosongkan, bisa diunggah menyusul.</div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditSertifikasi" tabindex="-1" aria-labelledby="modalEditSertifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditSertifikasi" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fs-6" id="modalEditSertifikasiLabel"><i class="fas fa-edit me-2"></i>Edit Sertifikasi / Pelatihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Terkait Parameter Uji <span class="text-muted fw-normal">(Opsional)</span></label>
                        <select name="parameter_uji_id" id="editParameterUji" class="form-select form-select-sm" onchange="autoFillJenisSertifikasi(this, 'editJenisSertifikasi')">
                            <option value="">-- Pelatihan Umum / Non-Parameter --</option>
                            @foreach($parameterList as $param)
                                <option value="{{ $param->parameter_uji_id }}">{{ $param->nama_parameter }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jenis Sertifikasi / Pelatihan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_sertifikasi" id="editJenisSertifikasi" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Sertifikat</label>
                        <input type="text" name="no_sertifikasi" id="editNoSertifikasi" class="form-control form-control-sm">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" id="editTanggalTerbit" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" id="editTanggalBerakhir" class="form-control form-control-sm">
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Kosongkan bila tidak terbatas.</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Dokumen Sertifikat</label>
                        <input type="file" name="file_sertifikat" class="form-control form-control-sm" accept="image/*,application/pdf">
                        <div class="form-text text-muted" style="font-size: 0.75rem;" id="editFileInfo">Kosongkan bila tidak ingin mengganti dokumen yang sudah ada.</div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm text-white"><i class="fas fa-save me-1"></i> Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEdit = document.getElementById('modalEditSertifikasi');
        if (! modalEdit) return;

        modalEdit.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            document.getElementById('formEditSertifikasi').action = button.getAttribute('data-action');
            document.getElementById('editParameterUji').value = button.getAttribute('data-parameter') || '';
            document.getElementById('editJenisSertifikasi').value = button.getAttribute('data-jenis') || '';
            document.getElementById('editNoSertifikasi').value = button.getAttribute('data-no') || '';
            document.getElementById('editTanggalTerbit').value = button.getAttribute('data-terbit') || '';
            document.getElementById('editTanggalBerakhir').value = button.getAttribute('data-berakhir') || '';

            const hasFile = button.getAttribute('data-has-file') === '1';
            document.getElementById('editFileInfo').textContent = hasFile
                ? 'Sudah ada dokumen tersimpan. Kosongkan bila tidak ingin menggantinya.'
                : 'Belum ada dokumen. Unggah di sini bila tersedia.';
        });
    });

    function autoFillJenisSertifikasi(selectElement, targetId) {
        const targetInput = document.getElementById(targetId);
        if (selectElement.value && selectElement.options[selectElement.selectedIndex].text !== '-- Pelatihan Umum / Non-Parameter --') {
            targetInput.value = selectElement.options[selectElement.selectedIndex].text;
        }
    }
</script>
@endif
@endsection