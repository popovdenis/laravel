<h1 class="text-3xl font-bold mb-2 text-gray-900">{{ $post->title }}</h1>
@if($post->subtitle)
    <h2 class="text-xl text-gray-600 mb-4">{{ $post->subtitle }}</h2>
@endif

<div class="text-center p-4">
    <img src="{{ $post->getImage('medium') }}" alt="{{ $post->title }}" class="mx-auto rounded">
</div>

<div class="prose max-w-none">
    @if ($contentMode === 'blocks' && filled($blocks))
        <x-page-builder :blocks="$blocks" />
    @else
        {!! $description !!}
    @endif
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
