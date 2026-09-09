@if($selectedParameter)
<div class="mb-4">
    <ul class="nav nav-pills nav-fill bg-white shadow-sm rounded p-2 gap-2">
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ ($jenisGrafik ?? 'in_house') == 'in_house' ? 'active bg-primary text-white fw-bold shadow' : 'text-dark bg-light border' }}" 
               href="{{ route('parameter-uji.show', ['parameter_uji' => $selectedParameter->parameter_uji_id, 'tab' => 'in_house']) }}">
                <i class="fas fa-chart-line me-2"></i>In-House Control
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ ($jenisGrafik ?? 'in_house') == 'crm' ? 'active bg-primary text-white fw-bold shadow' : 'text-dark bg-light border' }}" 
               href="{{ route('parameter-uji.show', ['parameter_uji' => $selectedParameter->parameter_uji_id, 'tab' => 'crm']) }}">
                <i class="fas fa-certificate me-2"></i>CRM (Sertifikat Pabrik)
            </a>
        </li>
    </ul>
</div>
@endif
