<link rel="stylesheet" type="text/css" media="all" href="{{asset('css/comments.css')}}" />

@if ($user->hasNewComments())
    <script type="text/javascript" src="{!! asset('js/comment-reply.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/comment-scripts.js') !!}"></script>

    <div class="btn-group">
        <button data-toggle="dropdown"
                class="btn btn-primary">{{ trans('comments.new_comments_title') }} ({{ $user->new_comments }})
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-coments-menu">
            @foreach($user->newComments() as $comment)
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


    <div class="modal fade" id="newCommentsModal" tabindex="-1" role="dialog"
         aria-labelledby="newCommentsModal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="newCommentsLabel">{{ trans('comments.new_comments') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="comments-block">
                    @foreach($user->newComments() as $comment)
                        <div>
                            <span><?php echo substr($comment->text, 0, 15) ?>...</span>
                        </div>
                    @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ trans('comments.popup.close') }}</button>
                </div>
            </div>
        </div>
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
