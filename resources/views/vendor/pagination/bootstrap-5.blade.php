@if ($paginator->hasPages())
    <nav class="d-flex flex-column justify-content-center align-items-center py-3">

        {{-- الأرقام --}}
        <ul class="pagination pagination-rounded mb-2"> {{-- mb-2 عشان يبعد عن الكلام اللي تحته --}}
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            {{-- Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
            @endif
        </ul>

        {{-- النص اللي تحت --}}
        <div class="small text-muted">
            Showing <span class="fw-bold text-dark">{{ $paginator->firstItem() }}</span> to
            <span class="fw-bold text-dark">{{ $paginator->lastItem() }}</span> of
            <span class="fw-bold text-dark">{{ $paginator->total() }}</span> results
        </div>
    </nav>
@endif
