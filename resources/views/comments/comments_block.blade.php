<?php
$comments = $photo->comments()->get()->all();
?>
<!--Блок для вывода сообщения про отправку комментария-->
<div class="wrap_result"></div>

<div id="comments">
    <ol class="commentlist group">
    <?php if (!empty($comments)) : ?>
    <?php foreach($comments as $k => $comment) : ?>
    <!--Выводим только родительские комментарии parent_id = 0-->
        @if ($comment->isParent())
            @include('comments.comment', ['item' => $comment])
        @endif
        <?php endforeach; ?>
        <?php endif ?>
    </ol>

    <div id="respond-{{ $photo->id }}">
        <h3 id="reply-title">Написать <span>комментарий</span>
            <small>
                <a rel="nofollow" id="cancel-comment-reply-link" href="#respond-{{ $photo->id }}" style="display:none;">Отменить ответ</a>
            </small>
        </h3>
        <!--параметр action используется ajax-->
        {!! Form::open(['route' => 'comment.store','method'=>'POST', 'id' => 'commentform-' . $photo->id]) !!}
        <div class="form-group">
            <textarea class="form-control" name="text" cols="45" rows="8"></textarea>
        </div>
        <!--Данные поля так же нужны для работы JS - вставки формы сразу за комментарием на который нужно ответить-->
        <input type="hidden" id="comment_image_ID" name="comment_image_ID" value="{{ $photo->id }}">
        <input type="hidden" id="comment_parent" name="comment_parent" value="0">
        <div class="form-group">
            <input class="btn btn-default" name="submit" type="submit" id="submit" value="Отправить"/>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        if (window.location.hash) {
            var hash = window.location.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 800, function () {
                window.location.hash = hash;
            });
        }
    });
</script>
