var photoObject = {
    
    removePhotosUrl: null,
    downloadPhotosUrl: null,
    
    deletePhotosMode: false,
    downloadPhotosMode: false,
    
    init: function () {
        var self = this;
        
        $('.cancel-photos-btn').on('click', function () {
            self.deletePhotosMode = false;
            self.downloadPhotosMode = false;
            $(this).hide();
            self.fireMode();
        });
        
        self.deletePhotoEvent();
        self.downloadPhotoEvent();
    },
    
    getToken: function () {
        var _token = $('input[name="_token"]');
        if (_token.length > 0) {
            return $(_token.get(0)).val();
        }
    },
    
    deletePhotoEvent: function () {
        var self = this;
        $('.remove-photos-btn').on('click', function (e) {
            e.preventDefault();
            self.deletePhotosMode = true;
            self.fireMode();
        });
    },
    
    downloadPhotoEvent: function () {
        var self = this;
        $('.download-photos-btn').on('click', function (e) {
            e.preventDefault();
            self.downloadPhotosMode = true;
            self.fireMode();
        });
    },
    
    fireMode: function () {
        var self = this;
        if (self.deletePhotosMode === false && self.downloadPhotosMode === false) {
            $('.cancel-photos-btn').hide();
            $('.download-photos-btn').show();
            $('.remove-photos-btn').show();
            self.disableCheckboxes();
            self.hideRemoveSelectedPhotosButton();
            self.hideDownloadSelectedAlbumsButton();
        } else {
            if (self.deletePhotosMode === true) {
                $('.cancel-photos-btn').show();
                $('.download-photos-btn').hide();
                $('.remove-photos-btn').hide();
                
                self.enableCheckboxes();
                self.displayRemoveSelectedPhotosButton();
                self.hideDownloadSelectedAlbumsButton();
            }
            if (self.downloadPhotosMode === true) {
                $('.cancel-photos-btn').show();
                $('.download-photos-btn').hide();
                $('.remove-photos-btn').hide();
                
                self.enableCheckboxes();
                self.displayDownloadSelectedPhotosButton();
            }
        }
    },
    
    /** DOWNLOAD ALBUMS FUNCTIONALITY **/
    
    displayDownloadSelectedPhotosButton: function () {
        $('.download-selected-photos').show();
        this.initDownloadPhotosBtnEvent();
        this.disableDownloadPhotosButton();
        
        return false;
    },
    
    hideDownloadSelectedAlbumsButton: function () {
        $('.download-selected-photos').hide();
    },
    
    initDownloadPhotosBtnEvent: function () {
        var self = this;
        $('.download-selected-photos').off('click').on('click', function () {
            var photosToDownload = self.getCheckedCheckboxes();
            if (photosToDownload.length > 0) {
                $('body').append('<form id="download-multiple-photos" method="get" ' +
                    'action="' + self.downloadPhotosUrl + '"></form>');
                var form = $('#download-multiple-photos');
                form.append('<input type="hidden" name="albumid" value="' + $('input[name="album-id"]').val() + '">');
                photosToDownload.each(function () {
                    form.append('<input type="hidden" name="photosIds[]" value="' + $(this).val() + '">');
                });
                form.submit();
                form.remove();
            }
        });
        
        return false;
    },
    
    disableDownloadPhotosButton: function () {
        $('.download-selected-photos').prop('disabled', 'disabled');
    },
    
    enableDownloadPhotosButton: function () {
        $('.download-selected-photos').prop('disabled', false);
    },
    
    /** REMOVE ALBUMS FUNCTIONALITY  **/
    
    displayRemoveSelectedPhotosButton: function () {
        $('.delete-selected-photos').show();
        this.initRemovePhotosBtnEvent();
        this.disableRemovePhotosButton();
    },
    
    initRemovePhotosBtnEvent: function () {
        var self = this;
        $('.delete-selected-photos').off('click').on('click', function () {
            var photosToRemove = self.getCheckedCheckboxes();
            if (photosToRemove.length > 0) {
                $('body').append('<form id="remove-multiple-photos" method="get" ' +
                    'action="' + self.removePhotosUrl + '"></form>');
                
                var form = $('#remove-multiple-photos');
                form.append('<input type="hidden" name="albumid" value="' + $('input[name="album-id"]').val() + '">');
                photosToRemove.each(function () {
                    form.append('<input type="hidden" name="photosIds[]" value="' + $(this).val() + '">');
                });
                form.submit();
                form.remove();
            }
        });
    },
    
    disableRemovePhotosButton: function () {
        $('.delete-selected-photos').prop('disabled', 'disabled');
    },
    
    enableRemovePhotosButton: function () {
        $('.delete-selected-photos').prop('disabled', false);
    },
    
    hideRemoveSelectedPhotosButton: function () {
        $('.delete-selected-photos').hide();
    },
    
    enableCheckboxes: function () {
        var self = this;
        $('.photo-checkbox').each(function () {
            $(this).prop('checked', false).show();
            self.initCheckboxEvent($(this));
        });
    },
    
    disableCheckboxes: function () {
        $('.photo-checkbox').each(function () {
            $(this).prop('checked', false).hide();
        });
    },
    
    initCheckboxEvent: function (checkbox) {
        var self = this;
        checkbox.off('click').on('click', function () {
            if ($(this).is(':checked')) {
                if (self.deletePhotosMode) {
                    self.enableRemovePhotosButton();
                }
                if (self.downloadPhotosMode) {
                    self.enableDownloadPhotosButton();
                }
            } else {
                if (self.getCheckedCheckboxes().length > 0) {
                    if (self.deletePhotosMode) {
                        self.enableRemovePhotosButton();
                    }
                    if (self.downloadPhotosMode) {
                        self.enableDownloadPhotosButton();
                    }
                } else {
                    if (self.deletePhotosMode) {
                        self.disableRemovePhotosButton();
                    }
                    if (self.downloadPhotosMode) {
                        self.disableDownloadPhotosButton();
                    }
                }
            }
        });
    },
    
    getCheckedCheckboxes: function () {
        return $('.photos-list').find('.photo-checkbox:checked');
    }
};
