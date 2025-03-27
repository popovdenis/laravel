@php
    $name_chain = $name_chain ?? '';
    $routeWithoutLocale = $routeWithoutLocale ?? false;
    $locale = $locale ?? app()->getLocale();
@endphp
@foreach($category_tree as $category)
    @php
        $trans = $category->categoryTranslations->first();
    @endphp

    @if($trans)
        @php
            $name_chain = $name_chain . '/' . $trans->slug;
            $url = route('blog.view_category', [ltrim($name_chain, '/')]);
        @endphp

        <li class="ml-4 mt-1 text-sm text-gray-700">
            <a href="{{ $url }}" class="hover:underline text-blue-600">
                {{ $trans->category_name }}
            </a>

            @if($category->siblings->isNotEmpty())
                <ul class="ml-4 border-l border-gray-300 pl-4">
                    @include('blog::partials._category_partial', [
                        'category_tree' => $category->siblings,
                        'name_chain' => $name_chain,
                        'routeWithoutLocale' => $routeWithoutLocale,
                        'locale' => $locale,
                    ])
                </ul>
            @endif
        </li>
    @endif
@endforeach
