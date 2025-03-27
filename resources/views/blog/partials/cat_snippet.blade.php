@forelse($category->siblings as $category2)
    <a href="{{ $category2->url() }}">
        <h6 class="pl-5 text-sm text-gray-800 hover:underline">{{ $category2->category_name }}</h6>
    </a>

    @forelse($category2->siblings as $category3)
        <a href="{{ $category3->url() }}">
            <h6 class="pl-10 text-sm text-gray-700 hover:underline">{{ $category3->category_name }}</h6>
        </a>
    @empty
    @endforelse
@empty
@endforelse
