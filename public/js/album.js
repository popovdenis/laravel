var albumObject = {
    files: [],
    saveAlbumUrl: null,
    albumUpdateUrl: null,
    uploadFilesUrl: null,
    uploadPhotoAlbumUrl: null,
    removeAlbumsUrl: null,
    
    init: function () {
        var self = this;
        
        $('.btn-save-album').on('click', function () {
            self.saveAlbum();
        });
        $('.edit-album').on('click', function () {
            self.updateAlbum();
        });
        $('.upload-photo').on('click', function () {
            self.uploadPhotoAlbum();
        });
        $('.album-add-photo-popup').click(function () {
            $('.upload-photo').prop('disabled', 'disabled');
        });
    
        self.deleteAlbumEvent();
    },
    
    getToken: function () {
        var _token = $('input[name="_token"]');
        if (_token.length > 0) {
            return $(_token.get(0)).val();
        }
    },
    
    saveAlbum: function () {
        var params = {
            '_token': albumObject.getToken(),
            'title': $('#albumStoreForm').find('input[name="title"]').val()
        };
        $.ajax({
            type:'POST',
            url: albumObject.saveAlbumUrl,
            data: params,
            success: function(data) {
                window.location.reload();
            }
        });
    },
    
    updateAlbum: function () {
        var params = {
            "_token": albumObject.getToken(),
            "title": $('#editAlbumModal').find('.album-name').val()
        };
        $.ajax({
            type: 'PUT',
            url: albumObject.updateAlbumUrl,
            data: params,
            success: function (response) {
                var messageBlock = $('.response-message');
                if (response) {
                    if (response.message != undefined) {
                        messageBlock.addClass('alert-success');
                        messageBlock.html(response.message);
                    }
                    window.location.reload();
                }
            }
        });
    },
    
    deleteAlbumEvent: function () {
        var self = this;
        $('.remove-albums-btn').on('click', function () {
            $(this).hide();
            $('.cancel-albums-btn').show();
            self.displayRemoveSelectedAlbumsButton();
            self.enableCheckboxes();
        });
        $('.cancel-albums-btn').on('click', function () {
            $(this).hide();
            $('.remove-albums-btn').show();
            self.hideRemoveSelectedAlbumsButton();
            self.disableCheckboxes();
        });
    },
    
    displayRemoveSelectedAlbumsButton: function () {
        $('.delete-selected-albums').show();
        this.initRemoveAlbumsBtnEvent();
        this.disableRemoveAlbumsButton();
    },
    
    disableRemoveAlbumsButton: function () {
        $('.delete-selected-albums').prop('disabled', 'disabled');
    },
    
    enableRemoveAlbumsButton: function () {
        $('.delete-selected-albums').prop('disabled', false);
    },
    
    hideRemoveSelectedAlbumsButton: function () {
        $('.delete-selected-albums').hide();
    },
    
    initRemoveAlbumsBtnEvent: function () {
        var self = this;
        $('.delete-selected-albums').off('click').on('click', function () {
            var albumsToRemove = self.getCheckedCheckboxes();
            if (albumsToRemove.length > 0) {
                var albumsToRemoveIds = [];
                albumsToRemove.each(function () {
                    albumsToRemoveIds.push($(this).val());
                });
                
                var params = {
                    "_token": self.getToken(),
                    "albumsIds": albumsToRemoveIds
                };
                $.ajax({
                    type: 'POST',
                    url: self.removeAlbumsUrl,
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
        $('.album-checkbox').each(function () {
            $(this).prop('checked', false).show();
            self.initCheckboxEvent($(this));
        });
    },
    
    disableCheckboxes: function () {
        $('.album-checkbox').each(function () {
            $(this).prop('checked', false).hide();
        });
    },
    
    initCheckboxEvent: function (checkbox) {
        var self = this;
        checkbox.off('click').on('click', function () {
            if ($(this).is(':checked')) {
                self.enableRemoveAlbumsButton();
            } else {
                if (self.getCheckedCheckboxes().length > 0) {
                    self.enableRemoveAlbumsButton();
                } else {
                    self.disableRemoveAlbumsButton();
                }
            }
        });
    },
    
    getCheckedCheckboxes: function () {
        return $('.albums-list').find('.album-checkbox:checked');
    },
    
    uploadPhotoAlbum: function () {
        var params = {
            "_token": albumObject.getToken(),
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
                    }
                    window.location.reload();
                }
            }
        });
    },
    
    initDropzone: function () {
        var self = this;
        var params = {
            "_token": albumObject.getToken()
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
            },
            complete: function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    $('.upload-photo').prop('disabled', false);
                }
            }
        });
    }
};
