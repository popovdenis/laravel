@if ($paginator->hasPages())
    <div class="flex justify-center mt-6">
        <nav class="inline-flex items-center gap-1" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-base text-gray-400 border border-gray-300 rounded bg-gray-100 cursor-not-allowed">
                    {{ __('«') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-base text-gray-700 border border-gray-300 rounded hover:bg-gray-100">
                    {{ __('«') }}
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 text-base text-gray-500 border border-gray-300 bg-white">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 text-base text-white border border-gray-300 bg-blue-600 font-semibold rounded">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-base text-gray-700 border border-gray-300 hover:bg-gray-100 rounded">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-base text-gray-700 border border-gray-300 rounded hover:bg-gray-100">
                    {{ __('»') }}
                </a>
            @else
                <span class="px-4 py-2 text-base text-gray-400 border border-gray-300 rounded bg-gray-100 cursor-not-allowed">
                    {{ __('»') }}
                </span>
            @endif
        </nav>
    </div>
@endif