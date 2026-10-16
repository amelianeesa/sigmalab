@props(['nama' => 'chemist', 'judul' => '', 'sub' => null])
@php
    $ekstensi = file_exists(public_path('images/maskot/' . $nama . '.png')) ? 'png' : 'webp';
@endphp
<div {{ $attributes->merge(['class' => 'sl-banner']) }}>
    <div class="sl-banner-text">
        <h1 class="sl-banner-title">{{ $judul }}</h1>
        @if($sub)<p class="sl-banner-sub">{{ $sub }}</p>@endif
        @if(!$slot->isEmpty())<div class="sl-banner-actions">{{ $slot }}</div>@endif
    </div>
    <img class="sl-banner-img" src="{{ asset('images/maskot/' . $nama . '.' . $ekstensi) }}" alt="" loading="lazy" decoding="async">
</div>