@props(['nama' => 'chemist', 'judul' => '', 'sub' => null])
{{--
    Banner karakter kartun di atas halaman.
    Pakai:  <x-maskot-banner nama="chemist" judul="Judul Halaman" sub="Keterangan singkat" />
    `nama` = nama file di public/images/maskot (tanpa .webp): chemist, analist, preparator, sampler, laporan
    Isi di dalam tag (opsional) tampil sebagai area tombol di bawah subjudul.
--}}
<div {{ $attributes->merge(['class' => 'sl-banner']) }}>
    <div class="sl-banner-text">
        <h1 class="sl-banner-title">{{ $judul }}</h1>
        @if($sub)<p class="sl-banner-sub">{{ $sub }}</p>@endif
        @if(!$slot->isEmpty())<div class="sl-banner-actions">{{ $slot }}</div>@endif
    </div>
    <img class="sl-banner-img" src="{{ asset('images/maskot/' . $nama . '.webp') }}" alt="" loading="lazy" decoding="async">
</div>
