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
        } else {
            if (self.deletePhotosMode === true) {
                $('.cancel-photos-btn').show();
                $('.download-photos-btn').hide();
                $('.remove-photos-btn').hide();
                
                self.enableCheckboxes();
                self.displayRemoveSelectedPhotosButton();
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
    
    initDownloadPhotosBtnEvent: function () {
        var self = this;
        $('.download-selected-photos').off('click').on('click', function () {
            var photosToDownload = self.getCheckedCheckboxes();
            if (photosToDownload.length > 0) {
                var photosToDownloadIds = [];
                photosToDownload.each(function () {
                    photosToDownloadIds.push($(this).val());
                });
                
                var params = {
                    "_token": self.getToken(),
                    "albumid": $('input[name="album-id"]').val(),
                    "photosIds": photosToDownloadIds
                };
                $.ajax({
                    type: 'POST',
                    url: self.downloadPhotosUrl,
                    data: params,
                    success: function (response) {
                        if (response) {
                            // window.location.reload();
                        }
                    }
                });
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
                var photosToRemoveIds = [];
                photosToRemove.each(function () {
                    photosToRemoveIds.push($(this).val());
                });
                
                var params = {
                    "_token": self.getToken(),
                    "albumid": $('input[name="album-id"]').val(),
                    "photosIds": photosToRemoveIds
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
