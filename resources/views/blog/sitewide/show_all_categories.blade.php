<h5 class="text-lg font-semibold mb-3">Post Categories</h5>
<ul class="space-y-2">
    @foreach(\App\Blog\Models\Category::orderBy("category_name")->limit(200)->get() as $category)
        <li>
            <a href="{{ $category->url() }}" class="text-blue-600 hover:underline block">
                {{ $category->category_name }}
            </a>
        </li>
    @endforeach
</ul>
