<div id="comments">
    <ol class="commentlist group">
    <?php if (!empty($comments)) : ?>
    <?php foreach($comments as $k => $comment) : ?>
    <!--Выводим только родительские комментарии parent_id = 0-->
        <?php if($k): ?>
        @break
        <?php endif ?>
            @include('comments.comment', ['item' => $comment])
        <?php endforeach; ?>
        <?php endif ?>
    </ol>
</div>
