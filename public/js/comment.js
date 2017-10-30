var commentObject = {
    getComentsModalName: null,
    getNewCommentsUrl: null,
    
    init: function () {
        $('.show-new-comments').on('click', function () {
            commentObject.getNewComments();
        });
        this.initReply()
    },
    
    initReply: function () {
        $('.comment-reply-link').each(function () {
            $(this).on('click', function () {
                var commentId = $(this).data('comment-id');
                var photoId = $(this).data('photo-id');
                
                return addComment.moveForm('comment-' + commentId, commentId, 'respond', photoId);
            });
        });
    },
    
    getNewComments: function () {
        var params = {
            "_token": $('input[name="comments_token"]').val()
        };
        $.ajax({
            type: 'POST',
            url: commentObject.getNewCommentsUrl,
            data: params,
            success: function (response) {
                if (response.new_comments != undefined) {
                    $(commentObject.getComentsModalName).find('.comments-block').html(response.new_comments);
                }
            }
        });
    }
};
