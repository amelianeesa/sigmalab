@if ($paginator->hasPages())
    @php
        $current  = $paginator->currentPage();
        $last     = $paginator->lastPage();
        $maxLinks = 3; // jumlah maksimal nomor halaman yang tampil

        $start = max(1, $current - 1);
        $end   = min($last, $start + $maxLinks - 1);
        $start = max(1, $end - $maxLinks + 1);
    @endphp

    <nav class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div class="text-muted" @if (($size ?? '') === 'sm') style="font-size: 0.72rem;" @endif>
            Showing
            <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
            to
            <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
            of
            <span class="fw-semibold">{{ $paginator->total() }}</span>
            results
        </div>

        <ul class="pagination {{ !empty($size) ? 'pagination-' . $size : '' }} mb-0">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
                </li>
            @endif

            {{-- Titik-titik di kiri --}}
            @if ($start > 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            {{-- Nomor halaman (maks 3) --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endfor

            {{-- Titik-titik di kanan --}}
            @if ($end < $last)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif