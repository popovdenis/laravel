<div class="add_comment_area">
    <h5 class="text-center">Add a comment</h5>

    <form method="POST" action="{{ route('blog.comments.add_new_comment', ['blogPostSlug' => $post->slug]) }}">
        @csrf

        <div class="form-group">
            <label for="comment">Your Comment</label>
            <textarea
                id="comment"
                name="comment"
                class="form-control"
                rows="7"
                placeholder="Write your comment here"
                required
            >{{ old('comment') }}</textarea>
        </div>

        <div class="row">
            @if(!auth()->check() || config('blog.comments.save_user_id_if_logged_in') === false)
                <div class="col">
                    <div class="form-group">
                        <label for="author_name">Your Name</label>
                        <input
                            type="text"
                            name="author_name"
                            id="author_name"
                            class="form-control"
                            placeholder="Your name"
                            value="{{ old('author_name') }}"
                            required
                        >
                    </div>
                </div>

                @if(config('blog.comments.ask_for_author_email'))
                    <div class="col">
                        <div class="form-group">
                            <label for="author_email">
                                Your Email <small>(won't be displayed publicly)</small>
                            </label>
                            <input
                                type="email"
                                name="author_email"
                                id="author_email"
                                class="form-control"
                                placeholder="Your Email"
                                value="{{ old('author_email') }}"
                                required
                            >
                        </div>
                    </div>
                @endif
            @endif

            @if(config('blog.comments.ask_for_author_website'))
                <div class="col">
                    <div class="form-group">
                        <label for="author_website">
                            Your Website <small>(will be displayed)</small>
                        </label>
                        <input
                            type="url"
                            name="author_website"
                            id="author_website"
                            class="form-control"
                            placeholder="Your Website URL"
                            value="{{ old('author_website') }}"
                        >
                    </div>
                </div>
            @endif
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-success btn-block">
                Add Comment
            </button>
        </div>
    </form>
</div>
