@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Hasil Uji</h1>
        @can('create', App\Models\HasilUji::class)
        <a href="{{ route('hasil-uji.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Input Hasil Uji Baru
        </a>
        @endcan
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('hasil-uji.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kegiatan_id" class="form-label">Kegiatan / Kode Sampel</label>
                        <select class="form-select" id="kegiatan_id" name="kegiatan_id">
                            <option value="">Semua Kegiatan</option>
                            @foreach($kegiatanList as $keg)
                                <option value="{{ $keg->kegiatan_id }}" {{ request('kegiatan_id') == $keg->kegiatan_id ? 'selected' : '' }}>
                                    {{ $keg->kode_sampel ?? 'Kegiatan ID: ' . $keg->kegiatan_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status_berketerimaan" class="form-label">Status Berketerimaan</label>
                        <select class="form-select" id="status_berketerimaan" name="status_berketerimaan">
                            <option value="">Semua Status</option>
                            <option value="inlier" {{ request('status_berketerimaan') == 'inlier' ? 'selected' : '' }}>Inlier</option>
                            <option value="outlier" {{ request('status_berketerimaan') == 'outlier' ? 'selected' : '' }}>Outlier</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search"></i> Filter</button>
                        <a href="{{ route('hasil-uji.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kegiatan (Kode Sampel)</th>
                            <th>Parameter Uji</th>
                            <th>Nilai Hasil</th>
                            <th>Batas (Bawah - Atas)</th>
                            <th>Status</th>
                            <th>Diinput Oleh</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasilUjiList as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->kegiatan->kode_sampel ?? $item->kegiatan_id }}</td>
                            <td>{{ $item->parameterUji->nama_parameter ?? '-' }}</td>
                            <td>{{ $item->nilai_hasil }}</td>
                            <td>
                                {{ $item->parameterUji->batas_bawah ?? '-' }} - {{ $item->parameterUji->batas_atas ?? '-' }}
                            </td>
                            <td>
                                @if(strtolower($item->status_berketerimaan) == 'inlier')
                                    <span class="badge bg-success">Inlier</span>
                                @elseif(strtolower($item->status_berketerimaan) == 'outlier')
                                    <span class="badge bg-danger">Outlier</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status_berketerimaan }}</span>
                                @endif
                            </td>
                            <td>{{ $item->pengguna->nama ?? '-' }}</td>
                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('hasil-uji.show', $item->hasil_uji_id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Data hasil uji tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($hasilUjiList, 'links'))
                <div class="mt-3">
                    {{ $hasilUjiList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
