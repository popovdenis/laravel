var commentObject = {
    getComentsModalName: null,
    getNewCommentsUrl: null,
    getMarkCommentsUrl: null,

    init: function () {
        $('.show-new-comments').on('click', function () {
            commentObject.getNewComments();
        });
        this.initReply();
        this.initOpenPhotoEvent();
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
            "_token": $($('input[name="_token"]')[0]).val()
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
    },

    initOpenPhotoEvent: function () {
        var self = this;
        $('.gallery-item-photo').find('a').each(function () {
            $(this).on('click', function () {
                self.markCommentsAsRead($(this));
            });
        });
    },

    markCommentsAsRead: function (link) {
        var photoId = $(link).data('photo-id');
        var params = {
            "_token": $($('input[name="_token"]')[0]).val(),
            "photo_id": photoId
        };
        $.ajax({
            type: 'POST',
            url: commentObject.getMarkCommentsUrl,
            data: params,
            success: function (response) {
                if (response.new_comments_count != undefined) {
                    $('.dropdown-coments-menu').find("[data-photo-id='" + photoId + "']").hide();
                    if (response.new_comments_count > 0) {
                        $('.new-comments-count').html('(' + response.new_comments_count + ')');
                    } else {
                        $('.comments-block').hide();
                        $('.comments-section')
                            .append('<span class="no-comments-block">У вас нет свежих комментариев</span>');
                    }
                }
            }
        });
    },

    displayPhoto: function (photoId, commentId, isRead) {
        if (isRead) {
            $('.photos-list').find("[data-photo-id='" + photoId + "']").find('a.photo-link').trigger('click');

            var hash = window.location.hash;
            $('html, body').animate({
                scrollTop: $('#comment-' + commentId).offset().top
            }, 800, function () {
                window.location.hash = hash;
            });
        }
    }
};
