var albumObject = {
    saveCommentUrl: null,
    
    init: function () {
        $('.edit-album').on('click', function () {
            albumObject.saveAlbum();
        });
    }
};
