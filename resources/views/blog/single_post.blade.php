<x-app-layout>
    <x-slot name="title">
        {{ $post->genSeoTitle() }}
    </x-slot>

    @push('head')
        <link type="text/css" href="{{ asset('css/blog.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/blog.js') }}"></script>
    @endpush

    @if(config("blog.reading_progress_bar"))
        <div id="scrollbar">
            <div id="scrollbar-bg"></div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 py-8">
        @include("blog::partials.show_errors")

        @include("blog::partials.full_post_details")

        @if(config("blog.comments.type_of_comments_to_show", "built_in") !== 'disabled')
            <div id="maincommentscontainer" class="mt-10">
                <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6" id="blogcomments">
                    Comments
                </h2>
                @include("blog::partials.show_comments")
            </div>
        @endif
    </div>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @foreach ($blocks as $block)
            @includeIf('blog::blocks.' . $block['type'], ['data' => $block['data']])
        @endforeach
    </div>
</x-app-layout>
