<div class="col-md-6">
    <div class="blog-item">

        <div class="text-center blog-image">
            {!! $post->image_tag('medium', true, '') !!}
        </div>

        <div class="blog-inner-item">
            <h3><a href="{{ $post->url($locale, $routeWithoutLocale) }}">{{ $post->title }}</a></h3>
            <h5>{{ $post->subtitle }}</h5>

            @if (config('blog.show_full_text_at_list'))
                <p>{!! $post->post_body_output() !!}</p>
            @else
                <p>{!! mb_strimwidth($post->post_body_output(), 0, 400, '...') !!}</p>
            @endif

            <div class="post-details-bottom">
                <span class="light-text">Authored by:</span> {{ $post->post->author?->name }}
                <span class="light-text">Posted at:</span> {{ $post->post->posted_at->format('d M Y') }}
            </div>

            <div class="text-center">
                <a href="{{ $post->url($locale, $routeWithoutLocale) }}" class="btn btn-primary">View Post</a>
            </div>
        </div>
    </div>
</div>
