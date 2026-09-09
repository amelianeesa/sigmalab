    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="parameter_uji_id" class="form-label">Parameter Uji</label>
                    <select class="form-select" id="parameter_uji_id" name="parameter_uji_id" required>
                        <option value="">-- Pilih Parameter --</option>
                        @foreach($parameterList as $param)
                            <option value="{{ $param->parameter_uji_id }}" {{ request('parameter_uji_id') == $param->parameter_uji_id ? 'selected' : '' }}>
                                {{ $param->nama_parameter }} ({{ $param->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($selectedParameter)
                        <a href="{{ route('hasil-uji.inhouse-control.cetak', ['parameter_uji_id' => request('parameter_uji_id'), 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir')]) }}" class="btn btn-danger flex-grow-1" target="_blank">
                            <i class="fas fa-file-pdf"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
