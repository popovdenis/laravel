var albumObject = {
    files: [],
    albumUpdateUrl: null,
    uploadFilesUrl: null,
    uploadPhotoAlbumUrl: null,
    removePhotosUrl: null,
    
    init: function () {
        $('.edit-album').on('click', function () {
            albumObject.saveAlbum();
        });
        $('.upload-photo').on('click', function () {
            albumObject.uploadPhotoAlbum();
        });
        albumObject.initDropzone();
        albumObject.initRemovePhotoLink();
    },
    
    saveAlbum: function () {
        var params = {
            "_token": $('input[name="_token"]').val(),
            "title": $('#albumNameModal').find('.album-name').val()
        };
        $.ajax({
            type: 'PUT',
            url: albumObject.albumUpdateUrl,
            data: params,
            success: function (response) {
                var messageBlock = $('.response-message');
                if (response) {
                    if (response.message != undefined) {
                        messageBlock.addClass('alert-success');
                        messageBlock.html(response.message);
                        
                        window.location.reload();
                    }
                } else {
                
                }
            }
        });
    },
    
    uploadPhotoAlbum: function () {
        var params = {
            "_token": $('input[name="_token"]').val(),
            "albumid": $('input[name="album-id"]').val(),
            "images": this.files
        };
        $.ajax({
            type: 'POST',
            url: albumObject.uploadPhotoAlbumUrl,
            data: params,
            success: function (response) {
                var messageBlock = $('.response-message');
                if (response) {
                    if (response.message != undefined) {
                        messageBlock.addClass('alert-success');
                        messageBlock.html(response.message);
                        
                        window.location.reload();
                    }
                } else {
                
                }
            }
        });
    },
    
    initDropzone: function () {
        var self = this;
        var params = {
            "_token": $('input[name="_token"]').val()
        };
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("div#dropzoneFileUpload", {
            url: self.uploadFilesUrl,
            params: params,
            addRemoveLinks: false,
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            success: function(file, response) {
                if (response.result && response.filename != undefined) {
                    self.files.push(response.filename);
                }
            }
        });
    },
    
    displayRemovePhotosButton: function () {
        $('.remove-photos-all-block').show();
        this.initRemovePhotosBtnEvent();
        this.disabledRemovePhotosButton();
    },
    
    hideRemovePhotosButton: function () {
        $('.remove-photos-all-block').hide();
    },

    initRemovePhotosBtnEvent: function () {
        $('.remove-photos-all-block').off('click').on('click', function () {
            var photosToRemove = albumObject.getCheckedCheckboxes();
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
                    url: albumObject.removePhotosUrl,
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

    enableRemovePhotosButton: function () {
        $('.remove-photos-all-block').find('button').prop('disabled', false);
    },

    disabledRemovePhotosButton: function () {
        $('.remove-photos-all-block').find('button').prop('disabled', 'disabled');
    },
    
    enableCheckboxes: function () {
        var self = this;
        $('.remove-photo-checkbox').each(function () {
            $(this).prop('checked', false).show();
            self.initCheckboxEvent($(this));
        });
    },
    
    disableCheckboxes: function () {
        $('.remove-photo-checkbox').each(function () {
            $(this).prop('checked', false).hide();
        });
    },
    
    getCheckedCheckboxes: function () {
        return $('.table-photos-list').find('.remove-photo-checkbox:checked');
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
    }
};
