@if ($paginator->hasPages())
    <style>
        /* Custom pagination styling */
        .pagination {
            margin: 20px 0;
            display: inline-block;
        }
        .pagination > li > a,
        .pagination > li > span {
            color: #4a90e2; /* Primary link color */
            background-color: #fff;
            border: 1px solid #4a90e2;
            margin: 0 3px;
            padding: 8px 14px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .pagination > li > a:hover,
        .pagination > li > span:hover {
            color: #fff;
            background-color: #4a90e2;
            border-color: #4a90e2;
        }
        .pagination > .active > span,
        .pagination > .active > span:hover {
            color: #fff;
            background-color: #2c82c9;
            border-color: #2c82c9;
            cursor: default;
        }
        .pagination > .disabled > span,
        .pagination > .disabled > a,
        .pagination > .disabled > a:hover {
            color: #999;
            background-color: #f7f7f7;
            border-color: #ddd;
            cursor: not-allowed;
        }
    </style>

    <nav aria-label="Page navigation" class="text-center">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span aria-hidden="true">&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }} <span class="sr-only">(current)</span></span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&raquo;</a></li>
            @else
                <li class="disabled"><span aria-hidden="true">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
