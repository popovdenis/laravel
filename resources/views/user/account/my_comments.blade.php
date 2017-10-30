<link rel="stylesheet" type="text/css" media="all" href="{{asset('css/comments.css')}}" />

@if ($user->hasNewComments())
    <script type="text/javascript" src="{!! asset('js/comment-reply.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/comment-scripts.js') !!}"></script>

    <button class="btn btn-primary show-new-comments"
            data-toggle="modal" data-target="#newCommentsModal">
        <i class="fa fa-edit"></i>{{ trans('comments.new_comments') }} ({{ $user->new_comments }})
    </button>

    <div class="modal fade" id="newCommentsModal" tabindex="-1" role="dialog"
         aria-labelledby="newCommentsModal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="newCommentsLabel">{{ trans('comments.new_comments') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="comments-block"></div>
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
