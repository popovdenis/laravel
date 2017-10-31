var albumObject = {
    files: [],
    saveAlbumUrl: null,
    albumUpdateUrl: null,
    uploadFilesUrl: null,
    uploadPhotoAlbumUrl: null,
    removeAlbumsUrl: null,
    downloadAlbumsUrl: null,
    
    deleteAlbumsMode: false,
    downloadAlbumsMode: false,
    
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
        $('.cancel-albums-btn').on('click', function () {
            self.deleteAlbumsMode = false;
            self.downloadAlbumsMode = false;
            $(this).hide();
            self.fireMode();
        });
    
        self.deleteAlbumEvent();
        self.downloadAlbumEvent();
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
    
    downloadAlbumEvent: function () {
        var self = this;
        $('.download-albums-btn').on('click', function () {
            self.downloadAlbumsMode = true;
            self.fireMode();
        });
    },
    
    deleteAlbumEvent: function () {
        var self = this;
        $('.remove-albums-btn').on('click', function () {
            self.deleteAlbumsMode = true;
            self.fireMode();
        });
    },
    
    fireMode: function () {
        var self = this;
        if (self.deleteAlbumsMode === false && self.downloadAlbumsMode === false) {
            $('.cancel-albums-btn').hide();
            $('.download-albums-btn').show();
            $('.remove-albums-btn').show();
            self.disableCheckboxes();
            self.hideRemoveSelectedAlbumsButton();
        } else {
            if (self.deleteAlbumsMode === true) {
                $('.cancel-albums-btn').show();
                $('.download-albums-btn').hide();
                $('.remove-albums-btn').hide();
        
                self.enableCheckboxes();
                self.displayRemoveSelectedAlbumsButton();
            }
            if (self.downloadAlbumsMode === true) {
                $('.cancel-albums-btn').show();
                $('.download-albums-btn').hide();
                $('.remove-albums-btn').hide();
        
                self.enableCheckboxes();
                self.displayDownloadSelectedAlbumsButton();
            }
        }
    },
    
    /** DOWNLOAD ALBUMS FUNCTIONALITY **/

    displayDownloadSelectedAlbumsButton: function () {
        $('.download-selected-albums').show();
        this.initDownloadAlbumsBtnEvent();
        this.disableDownloadAlbumsButton();
    },
    
    initDownloadAlbumsBtnEvent: function () {
        var self = this;
        $('.download-selected-albums').off('click').on('click', function () {
            var albumsToDownload = self.getCheckedCheckboxes();
            if (albumsToDownload.length > 0) {
                var albumsToDownloadIds = [];
                albumsToDownload.each(function () {
                    albumsToDownloadIds.push($(this).val());
                });
                
                var params = {
                    "_token": self.getToken(),
                    "albumsIds": albumsToDownloadIds
                };
                $.ajax({
                    type: 'GET',
                    url: self.downloadAlbumsUrl,
                    data: params,
                    success: function (response) {
                        if (response) {
                            // window.location.reload();
                        }
                    }
                });
            }
        });
    },
    
    disableDownloadAlbumsButton: function () {
        $('.download-selected-albums').prop('disabled', 'disabled');
    },
    
    enableDownloadAlbumsButton: function () {
        $('.download-selected-albums').prop('disabled', false);
    },
    
    /** REMOVE ALBUMS FUNCTIONALITY  **/
    
    displayRemoveSelectedAlbumsButton: function () {
        $('.delete-selected-albums').show();
        this.initRemoveAlbumsBtnEvent();
        this.disableRemoveAlbumsButton();
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
    
    disableRemoveAlbumsButton: function () {
        $('.delete-selected-albums').prop('disabled', 'disabled');
    },
    
    enableRemoveAlbumsButton: function () {
        $('.delete-selected-albums').prop('disabled', false);
    },
    
    hideRemoveSelectedAlbumsButton: function () {
        $('.delete-selected-albums').hide();
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
                if (self.deleteAlbumsMode) {
                    self.enableRemoveAlbumsButton();
                }
                if (self.downloadAlbumsMode) {
                    self.enableDownloadAlbumsButton();
                }
            } else {
                if (self.getCheckedCheckboxes().length > 0) {
                    if (self.deleteAlbumsMode) {
                        self.enableRemoveAlbumsButton();
                    }
                    if (self.downloadAlbumsMode) {
                        self.enableDownloadAlbumsButton();
                    }
                } else {
                    if (self.deleteAlbumsMode) {
                        self.disableRemoveAlbumsButton();
                    }
                    if (self.downloadAlbumsMode) {
                        self.disableDownloadAlbumsButton();
                    }
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
