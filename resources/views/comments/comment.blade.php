
    <li id="li-comment-{{$item->id}}" class="comment">
        <div id="comment-{{$item->id}}" class="comment-container">
            <div class="comment-author vcard">
                <img alt="" src="https://www.gravatar.com/avatar/{{md5($item->email)}}?d=mm&s=75" class="avatar"
                     height="75" width="75"/>
                <cite class="fn">{{$item->name}}</cite>
            </div>
            <!-- .comment-author .vcard -->
            <div class="comment-meta commentmetadata">
                <div class="intro">
                    <div class="commentDate">
                        {{ is_object($item->created_at) ? $item->created_at->format('d.m.Y в H:i') : ''}}
                    </div>
                </div>
                <div class="comment-body">
                    <p>{{ $item->text }}</p>
                </div>
                <div class="reply group">
                    <a class="comment-reply-link" href="#respond"
                       data-comment-id="{{$item->id}}" data-photo-id="{{$item->photo()->id}}">
                        {{ trans('comments.reply') }}
                    </a>
                </div>
            </div>
        </div>

        @foreach ($item->children as $comment)
            <ul class="children">
                @include('comments.comment', ['item' => $comment])
            </ul>
        @endforeach

    </li>
