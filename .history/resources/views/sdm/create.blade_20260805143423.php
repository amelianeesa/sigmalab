@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">SDM & Kompetensi</h2>
            <p class="text-muted mb-0">Profil personil, matriks kompetensi & sertifikasi</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Profil Personil</h5>
                <button type="button" class="btn btn-dark shadow-sm px-3 fw-semibold rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahPersonil">
                    + Tambah Personil
                </button>
            </div>

            <div class="mb-4">
                <select class="form-select form-select-lg rounded-3 border-secondary-subtle" onchange="if (this.value) window.location.href='?personil_id=' + this.value">
                    <option value="">— Pilih personil —</option>
                    @foreach($personil as $p)
                        <option value="{{ $p->personil_id }}" {{ isset($selectedPersonil) && $selectedPersonil->personil_id == $p->personil_id ? 'selected' : '' }}>
                            {{ $p->nama }} — {{ $p->jabatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(isset($selectedPersonil))
            <!-- Detail Profil Terpilih -->
            <div class="p-3 border rounded-4 bg-light bg-opacity-50">
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; font-size: 18px;">
                        {{ strtoupper(substr($selectedPersonil->nama, 0, 2)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $selectedPersonil->nama }}</h5>
                        <p class="text-muted small mb-1">{{ $selectedPersonil->jabatan }}</p>
                        @if($selectedPersonil->file_cv)
                            <a href="{{ route('sdm.cv', $selectedPersonil->personil_id) }}" target="_blank" class="text-decoration-none small text-primary fw-semibold">
                                <i class="bi bi-file-earmark-pdf me-1"></i> CV: Lihat Dokumen/Foto
                            </a>
                        @else
                            <span class="text-muted small">CV: Belum diunggah</span>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Matriks Kompetensi</span>
                    <div class="bg-white p-3 rounded-3 border d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-dark">Audit Mutu Internal</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">Ahli</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold">
                        + Tambah Kompetensi
                    </button>
                </div>

                <div>
                    <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Sertifikasi & Pelatihan</span>
                    <div class="bg-white p-3 rounded-3 border d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pelatihan K3 Laboratorium</h6>
                            <p class="text-muted small mb-0">Terbit 01 Mei 2023 • Berlaku s.d 01 Mei 2025</p>
                        </div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                            Harus sertifikasi ulang
                        </span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold">
                        + Tambah Sertifikasi / Pelatihan
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-3">Daftar Personil Lab & Kalibrasi</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Posisi</th>
                            <th class="py-3">Sertifikasi Terakhir</th>
                            <th class="py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil as $row)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $row->nama }}</td>
                            <td><span class="text-muted">{{ $row->jabatan }}</span></td>
                            <td>{{ $row->nama_sertifikasi ?? 'Belum ada' }}</td>
                            <td>
                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3">
                                    • Perlu Sertifikasi Ulang
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Belum ada data personil terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL TAMBAH PERSONIL ================= -->
<div class="modal fade" id="modalTambahPersonil" tabindex="-1" aria-labelledby="modalTambahPersonilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="max-height: 85vh;">
            
            <div class="modal-header bg-white px-4 py-3 border-bottom flex-shrink-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTambahPersonilLabel">Tambah Personil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column" style="overflow: hidden; height: 100%;">
                @csrf
                
                <div class="modal-body p-4 bg-white" style="overflow-y: auto; flex: 1 1 auto;">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control form-control-sm py-2" value="{{ old('nama') }}" placeholder="mis. Budi Santoso" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Posisi / Lab</label>
                        <input type="text" name="jabatan" class="form-control form-control-sm py-2" value="{{ old('jabatan') }}" placeholder="mis. Analis Lab Kimia" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Upload CV / Foto Dokumen</label>
                        <input type="file" name="file_cv" class="form-control form-control-sm py-2" accept="image/*,application/pdf">
                        <div class="form-text text-muted" style="font-size: 11px;">Format yang didukung: JPG, PNG, atau PDF (Maks. 2MB).</div>
                    </div>

                    <hr class="my-3 text-muted opacity-25">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Nama Sertifikasi / Pelatihan Awal</label>
                        <input type="text" name="nama_sertifikasi" class="form-control form-control-sm py-2" value="{{ old('nama_sertifikasi') }}" placeholder="mis. Pelatihan K3 Laboratorium">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark small">Tgl Terbit</label>
                            <input type="date" name="tgl_terbit" class="form-control form-control-sm py-2" value="{{ old('tgl_terbit', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark small">Tgl Berakhir</label>
                            <input type="date" name="tgl_berakhir" class="form-control form-control-sm py-2" value="{{ old('tgl_berakhir', date('Y-m-d', strtotime('+2 years'))) }}">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark small">Reminder Sebelum Jatuh Tempo</label>
                        <select name="reminder" class="form-select form-select-sm py-2">
                            <option value="H-30" {{ old('reminder') == 'H-30' ? 'selected' : '' }}>H-30</option>
                            <option value="H-60" {{ old('reminder') == 'H-60' ? 'selected' : '' }}>H-60</option>
                            <option value="H-90" {{ old('reminder') == 'H-90' ? 'selected' : '' }}>H-90</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer bg-light px-4 py-3 border-top flex-shrink-0">
                    <button type="button" class="btn btn-light border px-4 text-secondary fw-semibold btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark px-4 fw-semibold shadow-sm btn-sm" style="background-color: #0b1f36;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection