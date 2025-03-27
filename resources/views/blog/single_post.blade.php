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

    <div class='container'>
        <div class='row'>
            <div class='col-sm-12 col-md-12 col-lg-12'>

                @include("blog::partials.show_errors")
                @include("blog::partials.full_post_details")

                @if(config("blog.comments.type_of_comments_to_show", "built_in") !== 'disabled')
                    <div id='maincommentscontainer'>
                        <h2 class='text-center' id='blogcomments'>Comments</h2>
                        @include("blog::partials.show_comments")
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
