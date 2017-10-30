var photoObject = {
    
    removePhotosUrl: null,
    
    init: function () {
        photoObject.initRemovePhotoLink();
    },
    
    initRemovePhotoLink: function () {
        var self = this;
        $('.remove-photos-link').on('click', function () {
            $(this).hide();
            $('.cancel-remove-photos-link').show();
            self.displayRemovePhotosButton();
            self.enableCheckboxes();
        });
        $('.cancel-remove-photos-link').on('click', function () {
            $(this).hide();
            $('.remove-photos-link').show();
            self.hideRemovePhotosButton();
            self.disableCheckboxes();
        });
    },
    
    displayRemovePhotosButton: function () {
        $('.remove-photos-all-block').show();
        this.initRemovePhotosBtnEvent();
        this.disabledRemovePhotosButton();
    },
    
    initRemovePhotosBtnEvent: function () {
        var self = this;
        $('.remove-photos-all-block').off('click').on('click', function () {
            var photosToRemove = self.getCheckedCheckboxes();
            if (photosToRemove.length > 0) {
                var photosToRemoveIds = [];
                photosToRemove.each(function () {
                    photosToRemoveIds.push($(this).val());
                });
                
                var params = {
                    "_token": $('input[name="_token"]').val(),
                    "albumid": $('input[name="album-id"]').val(),
                    "photos": photosToRemoveIds
                };
                $.ajax({
                    type: 'POST',
                    url: self.removePhotosUrl,
                    data: params,
                    success: function (response) {
                        if (response) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    },
    
    enableCheckboxes: function () {
        var self = this;
        $('.remove-photo-checkbox').each(function () {
            $(this).prop('checked', false).show();
            self.initCheckboxEvent($(this));
        });
    },
    
    hideRemovePhotosButton: function () {
        $('.remove-photos-all-block').hide();
    },
    
    disableCheckboxes: function () {
        $('.remove-photo-checkbox').each(function () {
            $(this).prop('checked', false).hide();
        });
    },
    
    initCheckboxEvent: function (checkbox) {
        var self = this;
        checkbox.off('click').on('click', function () {
            if ($(this).is(':checked')) {
                self.enableRemovePhotosButton();
            } else {
                if (self.getCheckedCheckboxes().length > 0) {
                    self.enableRemovePhotosButton();
                } else {
                    self.disabledRemovePhotosButton();
                }
            }
        });
    },
    
    enableRemovePhotosButton: function () {
        $('.remove-photos-all-block').find('button').prop('disabled', false);
    },
    
    disabledRemovePhotosButton: function () {
        $('.remove-photos-all-block').find('button').prop('disabled', 'disabled');
    },
    
    getCheckedCheckboxes: function () {
        return $('.table-photos-list').find('.remove-photo-checkbox:checked');
    }
};
