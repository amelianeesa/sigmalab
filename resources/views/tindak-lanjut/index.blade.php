@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Riwayat Tindak Lanjut</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-1 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Tindak Lanjut</li>
                    </ol>
                </nav>
            </div>

            @can('create', App\Models\RiwayatTindakLanjut::class)
                <a href="{{ route('tindak-lanjut.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mt-2 mt-sm-0">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Catat Tindak Lanjut Baru
                </a>
            @endcan
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Tindak Lanjut</h6>
                <form action="{{ route('tindak-lanjut.index') }}" method="GET" class="form-inline live-search-form"
                    data-target="#table-container">
                    <select name="status_tindak_lanjut" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="belum_ditindaklanjuti" {{ (isset($filterStatus) && $filterStatus == 'belum_ditindaklanjuti') ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                        <option value="dalam_investigasi" {{ (isset($filterStatus) && $filterStatus == 'dalam_investigasi') ? 'selected' : '' }}>Dalam Investigasi</option>
                        <option value="selesai" {{ (isset($filterStatus) && $filterStatus == 'selesai') ? 'selected' : '' }}>
                            Selesai</option>
                    </select>
                </form>
            </div>
            <div class="card-body" id="table-container">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Hasil Uji ID</th>
                                <th>Parameter Uji</th>
                                <th>Kegiatan (Kode Sampel)</th>
                                <th>Status Tindak Lanjut</th>
                                <th>Ditindaklanjuti Oleh</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $index => $item)
                                <tr>
                                    <td>{{ $riwayat->firstItem() + $index }}</td>
                                    <td>{{ $item->hasil_uji_id }}</td>
                                    <td>{{ $item->hasilUji->parameterUji->nama_parameter ?? '-' }}</td>
                                    <td>{{ $item->hasilUji->kegiatan->kode_sampel ?? '-' }}</td>
                                    <td>
                                        @if($item->status_tindak_lanjut == 'belum_ditindaklanjuti')
                                            <span class="badge bg-warning">Belum Ditindaklanjuti</span>
                                        @elseif($item->status_tindak_lanjut == 'dalam_investigasi')
                                            <span class="badge bg-info">Dalam Investigasi</span>
                                        @elseif($item->status_tindak_lanjut == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            {{ $item->status_tindak_lanjut }}
                                        @endif
                                    </td>
                                    <td>{{ $item->ditindaklanjutiOleh->nama_pengguna ?? '-' }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('tindak-lanjut.show', $item->riwayat_tindak_lanjut_id) }}"
                                            class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data riwayat tindak lanjut.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    @if(method_exists($riwayat, 'links'))
                        {{ $riwayat->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection