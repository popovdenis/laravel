<div class="flex flex-wrap gap-2">
    @foreach($categories as $category)
        <a href="{{ $category->categoryTranslations[0]->url($locale, $routeWithoutLocale) }}"
           class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-100 transition">
            {{ $category->categoryTranslations[0]->category_name }}
        </a>
    @endforeach
</div>
