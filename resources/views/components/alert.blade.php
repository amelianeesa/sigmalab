@props(['type' => 'info', 'icon' => null])

@php
    $defaultIcon = match($type) {
        'success' => 'fa-check-circle',
        'danger' => 'fa-times-circle',
        'warning' => 'fa-exclamation-triangle',
        default => 'fa-info-circle'
    };
    
    $icon = $icon ?? $defaultIcon;
@endphp

<div class="alert alert-{{ $type }} shadow-sm" role="alert">
    <i class="fas {{ $icon }} me-2"></i> {{ $slot }}
</div>
