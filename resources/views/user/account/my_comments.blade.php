<link rel="stylesheet" type="text/css" media="all" href="{{asset('css/comments.css')}}" />

@if ($currentUser->hasNewComments())
    <script type="text/javascript" src="{!! asset('js/comment-reply.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/comment-scripts.js') !!}"></script>

    <div class="btn-group">
        <button data-toggle="dropdown"
                class="btn btn-primary">{{ trans('comments.new_comments_title') }} ({{ $currentUser->getNewCommentCount() }})
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
                            <img src="{{ url('/') }}/{{ $comment->photo()->path_thumb }}" /></a>
                    </span>
                    <span><strong><?php echo $comment->author()->getFullname() ?></strong></span>
                </li>
            @endforeach

        </ul>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            commentObject.getComentsModalName = '#newCommentsModal';
            commentObject.getNewCommentsUrl = "{{ url('/') }}" + '/comment/getNewComments';
            commentObject.init();
        });
    </script>

@else
    <span>{{ trans('comments.no_comments') }}</span>
@endif
