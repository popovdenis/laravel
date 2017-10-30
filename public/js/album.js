var albumObject = {
    files: [],
    saveAlbumUrl: null,
    albumUpdateUrl: null,
    uploadFilesUrl: null,
    uploadPhotoAlbumUrl: null,
    
    init: function () {
        $('.btn-save-album').on('click', function () {
            albumObject.saveAlbum();
        });
        $('.edit-album').on('click', function () {
            albumObject.updateAlbum();
        });
        $('.upload-photo').on('click', function () {
            albumObject.uploadPhotoAlbum();
        });
        $('.album-add-photo-popup').click(function () {
            $('.upload-photo').prop('disabled', 'disabled');
        });
        albumObject.initDropzone();
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
