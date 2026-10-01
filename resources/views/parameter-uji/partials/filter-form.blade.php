<style>
        .pu-filter-card .form-label { font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.2rem; }
        .pu-filter-card .form-control,
        .pu-filter-card .form-select { font-size: 0.8rem; padding: 0.32rem 0.6rem; min-height: 0; }
        .pu-filter-card .btn { font-size: 0.78rem; padding: 0.32rem 0.75rem; }
        .pu-filter-card .btn-corporate-blue {
            background-color: #1b3152; border-color: #1b3152; color: #fff;
        }
        .pu-filter-card .btn-corporate-blue:hover,
        .pu-filter-card .btn-corporate-blue:focus { background-color: #14253e; border-color: #14253e; color: #fff; }
    </style>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-3 border-0 pu-filter-card">
        <div class="card-header py-2" style="background-color: #1b3152; color: #ffffff; font-weight: 600; font-size: 0.85rem;">
            <i class="fas fa-filter me-1"></i> Filter Data
        </div>
        <div class="card-body p-3">
            <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="parameter_uji_id" class="form-label">Parameter Uji</label>
                    <select class="form-select form-select-sm" id="parameter_uji_id" name="parameter_uji_id" required>
                        <option value="">-- Pilih Parameter --</option>
                        @foreach($parameterList as $param)
                            <option value="{{ $param->parameter_uji_id }}" {{ request('parameter_uji_id') == $param->parameter_uji_id ? 'selected' : '' }}>
                                {{ $param->nama_parameter }} ({{ $param->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control form-control-sm" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control form-control-sm" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-corporate-blue btn-sm flex-grow-1 shadow-sm fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if($selectedParameter)
                        <a href="{{ route('hasil-uji.inhouse-control.cetak', ['parameter_uji_id' => request('parameter_uji_id'), 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir')]) }}" class="btn btn-danger btn-sm flex-grow-1 shadow-sm fw-semibold" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>