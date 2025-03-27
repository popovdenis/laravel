<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-6 text-center">Add a comment</h2>

    <form method="POST" action="{{ route('blog.comments.add_new_comment', ['blogPostSlug' => $post->slug]) }}">
        @csrf

        <div class="mb-4">
            <label for="comment" class="block font-medium text-sm text-gray-700">Your Comment</label>
            <textarea
                id="comment"
                name="comment"
                rows="6"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="Write your comment here"
                required
            >{{ old('comment') }}</textarea>
        </div>

        @if(!auth()->check() || config('blog.comments.save_user_id_if_logged_in') === false)
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="author_name" class="block font-medium text-sm text-gray-700">Your Name</label>
                    <input
                        type="text"
                        name="author_name"
                        id="author_name"
                        value="{{ old('author_name') }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Your name"
                    >
                </div>

                @if(config('blog.comments.ask_for_author_email'))
                    <div>
                        <label for="author_email" class="block font-medium text-sm text-gray-700">
                            Your Email <small class="text-gray-500">(won't be displayed)</small>
                        </label>
                        <input
                            type="email"
                            name="author_email"
                            id="author_email"
                            value="{{ old('author_email') }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Your email"
                        >
                    </div>
                @endif
            </div>
        @endif

        @if(config('blog.comments.ask_for_author_website'))
            <div class="mt-4">
                <label for="author_website" class="block font-medium text-sm text-gray-700">
                    Your Website <small class="text-gray-500">(will be displayed)</small>
                </label>
                <input
                    type="url"
                    name="author_website"
                    id="author_website"
                    value="{{ old('author_website') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Your website URL"
                >
            </div>
        @endif

        <div class="mt-6">
            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Add Comment
            </button>
        </div>
    </form>
</div>
