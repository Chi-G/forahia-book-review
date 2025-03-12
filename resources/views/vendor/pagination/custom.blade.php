@if ($paginator->hasPages())
    <nav class="flex justify-center space-x-2 mt-4">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 bg-blue-500 text-white rounded">Previous</a>
        @endif

        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 bg-blue-700 text-white rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 bg-blue-500 text-white rounded">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 bg-blue-500 text-white rounded">Next</a>
        @else
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded">Next</span>
        @endif
    </nav>
@endif
