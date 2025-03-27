@if(\View::exists($post->full_view_file_path()))
    @include("custom_blog_posts." . $post->use_view_file, ['post' => $post])
@else
    @if(\Auth::check() && \Auth::user()->canManageBlogPosts())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            Custom blog post blade view file
            <code class="bg-gray-100 px-1 py-0.5 rounded">{{ $post->full_view_file_path() }}</code> not found.
            <a href="https://github.com/binshops/laravel-blog" target="_blank" class="underline text-blue-600 hover:text-blue-800">
                See Laravel Blog Package help here
            </a>.
        </div>
    @else
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            Sorry, but there is an error showing that blog post. Please come back later.
        </div>
    @endif
@endif
