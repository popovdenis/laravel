<h5 class="text-lg font-semibold mb-3">Random Posts</h5>
<ul class="space-y-2">
    @foreach(\App\Blog\Models\Post::inRandomOrder()->limit(5)->get() as $post)
        <li>
            <a href="{{ $post->url() }}" class="text-blue-600 hover:underline block">
                {{ $post->title }}
            </a>
        </li>
    @endforeach
</ul>
