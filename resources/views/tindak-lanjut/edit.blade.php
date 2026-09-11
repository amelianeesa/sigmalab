@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Tindak Lanjut #{{ $tindakLanjut->riwayat_tindak_lanjut_id }}</h1>
        <a href="{{ route('tindak-lanjut.show', $tindakLanjut->riwayat_tindak_lanjut_id) }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Batal
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Investigasi</h6>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <p class="mb-1"><strong>Kode Sampel:</strong> {{ $tindakLanjut->hasilUji->kegiatan->kode_sampel ?? '-' }}</p>
                        <p class="mb-1"><strong>Parameter Uji:</strong> {{ $tindakLanjut->hasilUji->parameterUji->nama_parameter ?? '-' }}</p>
                        <p class="mb-0"><strong>Nilai Outlier:</strong> {{ $tindakLanjut->hasilUji->nilai_hasil ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('tindak-lanjut.update', $tindakLanjut->riwayat_tindak_lanjut_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="status_tindak_lanjut" class="form-label fw-bold">Status Tindak Lanjut <span class="text-danger">*</span></label>
                    <select name="status_tindak_lanjut" id="status_tindak_lanjut" class="form-select" required>
                        <option value="belum_ditindaklanjuti" {{ old('status_tindak_lanjut', $tindakLanjut->status_tindak_lanjut) == 'belum_ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                        <option value="dalam_investigasi" {{ old('status_tindak_lanjut', $tindakLanjut->status_tindak_lanjut) == 'dalam_investigasi' ? 'selected' : '' }}>Dalam Investigasi</option>
                        <option value="selesai" {{ old('status_tindak_lanjut', $tindakLanjut->status_tindak_lanjut) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="catatan_investigasi" class="form-label fw-bold">Kesimpulan Akhir / Tindakan Resmi</label>
                    <textarea name="catatan_investigasi" id="catatan_investigasi" class="form-control" rows="5" placeholder="Tuliskan kesimpulan akhir dari hasil diskusi, atau tindakan resmi yang telah dilakukan...">{{ old('catatan_investigasi', $tindakLanjut->catatan_investigasi) }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
