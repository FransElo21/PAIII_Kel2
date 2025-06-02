@if ($paginator->hasPages())
<nav aria-label="Pagination">
  <ul class="pagination pagination-pill justify-content-center">
    {{-- First Page Link --}}
    @if(!$paginator->onFirstPage())
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First">««</a>
      </li>
    @else
      <li class="page-item disabled"><span class="page-link">««</span></li>
    @endif

    {{-- Previous Page Link --}}
    @if($paginator->onFirstPage())
      <li class="page-item disabled"><span class="page-link">‹</span></li>
    @else
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">‹</a>
      </li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
      {{-- "Three Dots" Separator --}}
      @if (is_string($element))
        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
      @endif

      {{-- Array Of Links --}}
      @if (is_array($element))
        @foreach ($element as $page => $url)
          {{-- Show only first, last, and 1 page around current --}}
          @if ($page == 1
               || $page == $paginator->lastPage()
               || ($page >= $paginator->currentPage() - 1 && $page <= $paginator->currentPage() + 1))
            @if ($page == $paginator->currentPage())
              <li class="page-item active" aria-current="page">
                <span class="page-link">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">›</a>
      </li>
    @else
      <li class="page-item disabled"><span class="page-link">›</span></li>
    @endif

    {{-- Last Page Link --}}
    @if ($paginator->hasMorePages())
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last">»»</a>
      </li>
    @else
      <li class="page-item disabled"><span class="page-link">»»</span></li>
    @endif
  </ul>
</nav>
@endif
