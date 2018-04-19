<div class="comments-section">
    @if ($currentUser->hasNewComments())
        <div class="btn-group">
            <button data-toggle="dropdown"
                    class="btn btn-primary">{{ trans('comments.new_comments_title') }}
                ({{ $currentUser->getNewCommentCount() }})
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-coments-menu">
                @foreach($currentUser->newComments() as $comment)
                    <li>
                    <span>
                    <?php
                        $commentUrl = route('album.show', $comment->photo()->album()->id);
                        $commentUrl .= '#comment-' . $comment->id;
                        ?>
                        <a href="<?php echo $commentUrl ?>">
                            <img src="{{ url('/') }}/{{ $comment->photo()->path_thumb }}"/></a>
                    </span>
                        <span><strong><?php echo $comment->author()->getFullname() ?></strong></span>
                    </li>
                @endforeach

            </ul>
        </div>

    @else
        <span>{{ trans('comments.no_comments') }}</span>
    @endif
</div>
