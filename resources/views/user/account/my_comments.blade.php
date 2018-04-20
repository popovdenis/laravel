<div class="comments-section">
    @if ($currentUser->hasNewComments())
        <div class="btn-group comments-block">
            <button data-toggle="dropdown"
                    class="btn btn-primary">{{ trans('comments.new_comments_title') }}
                <span class="new-comments-count">(@include('user.account.new_comments_count'))</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-coments-menu">
                @foreach($currentUser->newComments() as $comment)
                    <li data-photo-id="{{ $comment->photo()->id }}">
                    <span>
                    <?php
                        $commentUrl = route('album.show', $comment->photo()->album()->id);
                        $query = http_build_query([
                            'photo' => $comment->photo()->id,
                            'comment' => $comment->id
                        ]);
                        $commentUrl .= '?' . $query;
                        ?>
                        <a href="<?php echo $commentUrl ?>">
                            <img src="{{ url('/') }}/public/{{ $comment->photo()->path_thumb }}"/></a>
                    </span>
                        <span><strong><?php echo $comment->author()->getFullname() ?></strong></span>
                    </li>
                @endforeach

            </ul>
        </div>

    @else
        <span class="no-comments-block">{{ trans('comments.no_comments') }}</span>
    @endif
</div>
