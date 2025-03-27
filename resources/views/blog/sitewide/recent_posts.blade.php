<h5 class="text-lg font-semibold mb-3">Recent Posts</h5>
<ul class="space-y-2">
    @foreach(\App\Blog\Models\Post::orderBy('posted_at', 'desc')->limit(5)->get() as $post)
        <li>
            <a href="{{ $post->url() }}" class="text-blue-600 hover:underline block">
                {{ $post->title }}
            </a>
        </li>
    @endforeach
</ul>
