@if ($paginator->hasPages())
    <ul class="pagination pagination-md justify-content-end">

        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><a class="page-link" href="#">&laquo;
                    Əvvəlki</a>
            </li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;
                    Əvvəlki</a>
            </li>
        @endif



        @foreach ($elements as $element)

            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif



            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                            <li class="page-item active my-active"><a class="page-link" href="#">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach



        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Növbəti
                    &raquo;</a>
            </li>
        @else
            <li class="disabled"><span></span></li>
                <li class="page-item disabled"><a class="page-link" href="#">Növbəti&raquo;</a>
                </li>
        @endif
    </ul>
@endif
