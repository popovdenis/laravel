<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Results for') }} {{ $query }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-3">
                @php $search_count = 0; @endphp

                @forelse ($search_results as $result)
                    @if (isset($result->indexable) && $result->indexable instanceof \App\Blog\Models\PostTranslation)
                        @php $search_count++; @endphp
                        <h2 class="text-lg font-semibold mb-2">Search result #{{ $search_count }}</h2>
                        @include('blog::partials.index_loop', ['post' => $result->indexable])
                    @else
                        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                            Unable to show this search result – unknown type
                        </div>
                    @endif
                @empty
                    <div class="bg-red-100 text-red-700 p-4 rounded">
                        Sorry, but there were no results!
                    </div>
                @endforelse
            </div>

            <div>
                <h6 class="text-lg font-semibold mb-2">Blog Categories</h6>
                <ul class="space-y-1 text-sm text-gray-700">
                    @if ($categories && count($categories))
                        @include('blog::partials._category_partial', [
                            'category_tree' => $categories,
                            'name_chain' => ''
                        ])
                    @else
                        <li><span>No Categories</span></li>
                    @endif
                </ul>
            </div>
        </div>

        @if (config('blog.search.search_enabled'))
            <div class="mt-8">
                @include('blog::sitewide.search_form')
            </div>
        @endif
    </div>
</x-app-layout>
