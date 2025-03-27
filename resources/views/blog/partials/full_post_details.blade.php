@if(Auth::check() && Auth::user()->canManageBlogPosts())
    <div class="flex justify-end mb-4">
        <a href="{{ $post->edit_url() }}"
           class="inline-flex items-center px-4 py-1.5 border border-gray-400 text-sm rounded-md text-gray-700 hover:bg-gray-100 transition">
            Edit Post
        </a>
    </div>
@endif

<h1 class="text-3xl font-bold mb-2 text-gray-900">{{ $post->title }}</h1>
@if($post->subtitle)
    <h2 class="text-xl text-gray-600 mb-4">{{ $post->subtitle }}</h2>
@endif

@if($post->getImage('medium'))
    <div class="my-6 text-center">
        {!! $post->image_tag('medium', false, 'mx-auto rounded') !!}
    </div>
@endif

<div class="prose max-w-none">
    {!! $post->post_body_output() !!}
</div>

<hr class="my-8">

<p class="text-sm text-gray-500">
    Posted <strong>{{ $post->post?->posted_at?->diffForHumans() }}</strong>
</p>

@if($post->author)
    @include('blog::partials.author', ['post' => $post])
@endif

@if($categories)
    @include('blog::partials.categories', ['categories' => $categories])
@endif
