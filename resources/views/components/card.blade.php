@props(['title', 'icon' => null, 'headerClass' => 'bg-white', 'bodyClass' => ''])

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between {{ $headerClass }}">
        <h6 class="m-0 font-weight-bold text-primary">
            @if($icon)<i class="fas {{ $icon }} me-2"></i>@endif{{ $title }}
        </h6>
        {{ $headerActions ?? '' }}
    </div>
    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>
