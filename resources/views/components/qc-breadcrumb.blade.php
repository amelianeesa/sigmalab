@props(['active' => 'In-House'])

<ol class="breadcrumb mb-1 mt-3 align-items-center">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('verifikasi-mutu.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
    
    <li class="breadcrumb-item active d-flex align-items-center">
        <div class="dropdown">
            <a class="text-decoration-none fw-bold dropdown-toggle" href="#" role="button" id="qcDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: #6c757d;">
                QC {{ $active }}
            </a>
            <ul class="dropdown-menu shadow-sm" aria-labelledby="qcDropdown">
                <li>
                    <a class="dropdown-item {{ $active === 'In-House' ? 'active' : '' }}" href="{{ route('qc-inhouse.index') }}">
                        <i class="fas fa-vial me-2"></i> QC In-House
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ $active === 'CRM' ? 'active' : '' }}" href="#">
                        <i class="fas fa-certificate me-2"></i> QC CRM
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ $active === 'Uji Banding' ? 'active' : '' }}" href="#">
                        <i class="fas fa-balance-scale me-2"></i> QC Uji Banding
                    </a>
                </li>
            </ul>
        </div>
    </li>
    {{ $slot }}
</ol>
