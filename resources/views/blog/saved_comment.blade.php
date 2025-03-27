{{-- resources/views/vendor/blog/saved_comment.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Saved comment
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Thanks! Your comment has been saved!</h3>

        @if (!config('blog.comments.auto_approve_comments', false))
            <p class="text-gray-600 mb-6">
                After an admin user approves the comment, it'll appear on the site!
            </p>
        @endif

        <a href="{{ $blog_post->url(app('request')->get('locale')) }}"
           class="inline-block px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Back to blog post
        </a>
    </div>
</x-app-layout>
