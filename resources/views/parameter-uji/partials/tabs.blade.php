@if($selectedParameter)
<div class="mb-3 pu-tabs-wrap">
    <ul class="nav nav-pills nav-fill bg-white shadow-sm rounded p-2 gap-2 pu-tabs">
        <li class="nav-item">
            <a class="nav-link rounded-pill pu-tab-link {{ ($jenisGrafik ?? 'in_house') == 'in_house' ? 'active' : '' }}" 
               href="{{ route('parameter-uji.show', ['parameter_uji' => $selectedParameter->parameter_uji_id, 'tab' => 'in_house']) }}">
                <i class="fas fa-chart-line me-1 me-sm-2"></i><span class="d-none d-sm-inline">In-House Control</span><span class="d-sm-none">In-House</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill pu-tab-link {{ ($jenisGrafik ?? 'in_house') == 'crm' ? 'active' : '' }}" 
               href="{{ route('parameter-uji.show', ['parameter_uji' => $selectedParameter->parameter_uji_id, 'tab' => 'crm']) }}">
                <i class="fas fa-certificate me-1 me-sm-2"></i><span class="d-none d-sm-inline">CRM (Sertifikat Pabrik)</span><span class="d-sm-none">CRM</span>
            </a>
        </li>
    </ul>
</div>

<style>
    .pu-tabs { padding: 0.35rem !important; }
    .pu-tab-link {
        font-size: 0.8rem !important;
        font-weight: 600;
        padding: 0.4rem 0.9rem !important;
        color: #334155;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    .pu-tab-link.active {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #1b3152 !important;
        box-shadow: 0 2px 6px rgba(27, 49, 82, 0.25);
    }
    .pu-tab-link i { font-size: 0.78rem; }

    @media (max-width: 575.98px) {
        .pu-tabs { gap: 0.35rem !important; }
        .pu-tab-link { text-align: center; font-size: 0.75rem !important; padding: 0.4rem 0.5rem !important; }
    }
</style>
@endif