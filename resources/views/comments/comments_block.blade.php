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

    <div id="respond">
        <h3 id="reply-title">Написать <span>комментарий</span>
            <small>
                <a rel="nofollow" id="cancel-comment-reply-link"
                   href="#respond" style="display:none;">Отменить ответ</a>
            </small>
        </h3>
        <!--параметр action используется ajax-->
        {!! Form::open(['route' => 'comment.store','method'=>'POST', 'id' => 'commentform-' . $photo->id]) !!}
            <p class="comment-form-author">
                <label for="author">Имя</label>
                <input id="name" name="name" type="text" value="" size="30" aria-required="true"/>
            </p>
            <p class="comment-form-email">
                <label for="email">Email</label>
                <input id="email" name="email" type="text" value="" size="30" aria-required="true"/>
            </p>
            <p class="comment-form-comment">
                <label for="comment">Ваш комментарий</label>
                <textarea id="comment" name="text" cols="45" rows="8"></textarea>
            </p>
            <!--Данные поля так же нужны для работы JS - вставки формы сразу за комментарием на который нужно ответить-->
            <input type="hidden" id="comment_image_ID" name="comment_image_ID" value="{{ $photo->id}}">
            <input type="hidden" id="comment_parent" name="comment_parent" value="0">
            <div class="clear"></div>
            <p class="form-submit">
                <input name="submit" type="submit" id="submit" value="Отправить"/>
            </p>
        {!! Form::close() !!}
    </div>
</div>
