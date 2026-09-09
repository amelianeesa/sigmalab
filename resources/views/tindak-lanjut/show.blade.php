@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <ol class="breadcrumb mb-1 mt-3">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tindak-lanjut.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tindak-lanjut.index') }}" class="text-decoration-none">Tindak Lanjut Outlier</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
    <h1 class="h3 mb-0 text-gray-800">Detail Tindak Lanjut</h1>
        </div>
        <div>
            @if($tindakLanjut->hasilUji && $tindakLanjut->hasilUji->status_berketerimaan !== 'inlier')
            <a href="{{ route('hasil-uji.edit', $tindakLanjut->hasil_uji_id) }}" class="btn btn-sm btn-warning shadow-sm me-2">
                <i class="fas fa-flask fa-sm text-white-50"></i> Edit/Uji Ulang Data
            </a>
            @endif
            @can('update', $tindakLanjut)
            <a href="{{ route('tindak-lanjut.edit', $tindakLanjut->riwayat_tindak_lanjut_id) }}" class="btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Update Investigasi
            </a>
            @endcan
            <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Tindak Lanjut #{{ $tindakLanjut->riwayat_tindak_lanjut_id }}</h6>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 250px;">Referensi Hasil Uji ID</th>
                    <td>: {{ $tindakLanjut->hasil_uji_id }}</td>
                </tr>
                <tr>
                    <th>Parameter Uji</th>
                    <td>: {{ $tindakLanjut->hasilUji->parameterUji->nama_parameter ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kegiatan (Kode Sampel)</th>
                    <td>: {{ $tindakLanjut->hasilUji->kegiatan->kode_sampel ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nilai Hasil Uji</th>
                    <td>: {{ $tindakLanjut->hasilUji->nilai_hasil ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status Keberterimaan</th>
                    <td>: 
                        @if(($tindakLanjut->hasilUji->status_berketerimaan ?? '') == 'inlier')
                            <span class="badge bg-success">Inlier</span>
                        @elseif(($tindakLanjut->hasilUji->status_berketerimaan ?? '') == 'outlier')
                            <span class="badge bg-danger">Outlier</span>
                        @else
                            {{ $tindakLanjut->hasilUji->status_berketerimaan ?? '-' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status Tindak Lanjut</th>
                    <td>: 
                        @if($tindakLanjut->status_tindak_lanjut == 'belum_ditindaklanjuti')
                            <span class="badge bg-warning">Belum Ditindaklanjuti</span>
                        @elseif($tindakLanjut->status_tindak_lanjut == 'dalam_investigasi')
                            <span class="badge bg-info">Dalam Investigasi</span>
                        @elseif($tindakLanjut->status_tindak_lanjut == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            {{ $tindakLanjut->status_tindak_lanjut }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Catatan Investigasi</th>
                    <td>: <br><div class="mt-2 p-3 bg-light border rounded">{{ $tindakLanjut->catatan_investigasi }}</div></td>
                </tr>
                <tr>
                    <th>Ditindaklanjuti Oleh</th>
                    <td>: {{ $tindakLanjut->ditindaklanjutiOleh->nama_pengguna ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Dibuat</th>
                    <td>: {{ $tindakLanjut->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Ruang Diskusi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-comments me-2"></i>Ruang Diskusi & Komentar</h6>
        </div>
        <div class="card-body">
            <!-- Daftar Komentar -->
            <div class="chat-history mb-4" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                @forelse($tindakLanjut->komentars as $komentar)
                    @php
                        $isSender = $komentar->users_id === Auth::id();
                    @endphp
                    <div class="d-flex mb-3 {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="d-flex flex-column {{ $isSender ? 'align-items-end' : 'align-items-start' }}" style="max-width: 75%;">
                            <div class="mb-1">
                                <small class="text-muted fw-bold">{{ $komentar->user->personil->nama_personil ?? $komentar->user->username }}</small>
                                <small class="text-muted ms-2">{{ $komentar->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="p-3 rounded {{ $isSender ? 'bg-primary text-white shadow-sm' : 'bg-light text-dark border shadow-sm' }}">
                                {!! nl2br(e($komentar->komentar)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-comment-slash fa-2x mb-3 text-gray-300"></i>
                        <p>Belum ada diskusi untuk investigasi ini.<br>Kirim pesan untuk memulai percakapan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Form Balasan -->
            <form action="{{ route('tindak-lanjut.komentar.store', $tindakLanjut->riwayat_tindak_lanjut_id) }}" method="POST">
                @csrf
                <div class="input-group shadow-sm">
                    <textarea name="komentar" class="form-control" rows="2" placeholder="Tulis komentar, saran, atau pertanyaan di sini..." required></textarea>
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="fas fa-paper-plane me-2"></i>Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Edit (Audit Trail) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Riwayat Edit Data Pengujian</h6>
        </div>
        <div class="card-body">
            @php
                $activities = $tindakLanjut->hasilUji ? $tindakLanjut->hasilUji->activities()->latest()->get() : collect();
            @endphp

            @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th>Waktu Edit</th>
                                <th>Diedit Oleh</th>
                                <th>Perubahan Nilai</th>
                                <th>Status Sebelumnya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            @if(isset($activity->properties['old']) && isset($activity->properties['old']['nilai_hasil']))
                            <tr>
                                <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $activity->causer->nama_lengkap ?? 'Sistem' }}</td>
                                <td>
                                    <span class="text-danger"><del>{{ $activity->properties['old']['nilai_hasil'] ?? '-' }}</del></span> 
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i> 
                                    <span class="text-success">{{ $activity->properties['attributes']['nilai_hasil'] ?? '-' }}</span>
                                </td>
                                <td>{{ $activity->properties['old']['status_berketerimaan'] ?? '-' }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">*Hanya menampilkan riwayat perubahan angka pengujian.</small>
            @else
                <p class="text-muted fst-italic mb-0">Belum ada riwayat edit/perubahan data pada pengujian ini.</p>
            @endif
        </div>
    </div>
</div>
<style>
    .chat-history::-webkit-scrollbar { width: 6px; }
    .chat-history::-webkit-scrollbar-track { background: #f1f1f1; }
    .chat-history::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    .chat-history::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
</style>
@endsection
