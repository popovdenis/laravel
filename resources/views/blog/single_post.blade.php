<x-app-layout>
    <x-slot name="title">
        {{ $post->gen_seo_title() }}
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
</x-app-layout>
