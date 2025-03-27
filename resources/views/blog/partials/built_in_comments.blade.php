@forelse($comments as $comment)
    <div class="bg-gray-100 border border-gray-300 rounded mb-4 shadow-sm">
        <div class="bg-gray-200 px-4 py-2 flex justify-between items-center rounded-t">
            <div>
                <span class="font-semibold text-gray-800">{{ $comment->author() }}</span>

                @if(config('blog.comments.ask_for_author_website') && $comment->author_website)
                    (<a href="{{ $comment->author_website }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">website</a>)
                @endif
            </div>

            <span class="text-sm text-gray-600" title="{{ $comment->created_at }}">
                {{ $comment->created_at->diffForHumans() }}
            </span>
        </div>

        <div class="bg-white px-4 py-3 rounded-b">
            <p class="text-gray-800 whitespace-pre-line">{!! nl2br(e($comment->comment)) !!}</p>
        </div>
    </div>
@empty
    <div class="bg-blue-50 text-blue-800 px-4 py-3 rounded border border-blue-300">
        No comments yet! Why don't you be the first?
    </div>
@endforelse

@if($comments->count() >= config('blog.comments.max_num_of_comments_to_show', 500))
    <p class="text-sm text-gray-600 mt-2">
        <em>
            Only the first {{ config('blog.comments.max_num_of_comments_to_show', 500) }} comments are shown.
        </em>
    </p>
@endif
