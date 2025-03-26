@if(Auth::check() && Auth::user()->canManageBlogPosts())
    <a href="{{ $post->edit_url() }}" class="btn btn-outline-secondary btn-sm float-end">Edit Post</a>
@endif

<h1 class="blog_title">{{ $post->title }}</h1>
<h5 class="blog_subtitle">{{ $post->subtitle }}</h5>

{!! $post->image_tag('medium', false, 'd-block mx-auto') !!}

<p class="blog_body_content">
    {!! $post->post_body_output() !!}
</p>

<hr/>

<p>
    Posted <strong>{{ $post->post?->posted_at?->diffForHumans() }}</strong>
</p>

@includeWhen($post->author, 'blog::partials.author', ['post' => $post])
@includeWhen($categories, 'blog::partials.categories', ['categories' => $categories])
