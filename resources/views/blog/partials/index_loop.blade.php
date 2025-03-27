<div class="w-full md:w-1/2 px-2 mb-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">

        <div class="text-center p-4">
            <img src="{{ $post->getImage('medium') }}" alt="{{ $post->title }}" class="mx-auto rounded">
        </div>

        <div class="px-5 pb-5 flex flex-col flex-grow">
            <h3 class="text-xl font-bold text-gray-800 hover:underline">
                <a href="{{ $post->url($locale, $routeWithoutLocale) }}">{{ $post->title }}</a>
            </h3>

            @if($post->subtitle)
                <p class="text-sm text-gray-500 mt-1 mb-3">{{ $post->subtitle }}</p>
            @endif

            <div class="text-gray-700 text-sm flex-grow">
                @if (config('blog.show_full_text_at_list'))
                    <p>{!! $post->post_body_output() !!}</p>
                @else
                    <p>{!! mb_strimwidth($post->post_body_output(), 0, 400, '...') !!}</p>
                @endif
            </div>

            <div class="text-xs text-gray-500 mt-4">
                <span class="block"><strong>Author:</strong> {{ $post->post->author?->name }}</span>
                <span class="block"><strong>Posted:</strong> {{ $post->post->posted_at->format('d M Y') }}</span>
            </div>

            <div class="mt-4">
                <a href="{{ $post->url($locale, $routeWithoutLocale) }}"
                   class="inline-block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    View Post
                </a>
            </div>
        </div>
    </div>
</div>
