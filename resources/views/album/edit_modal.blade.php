<div class="modal fade" id="editAlbumModal" tabindex="-1" role="dialog"
     aria-labelledby="editAlbumModal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="editAlbumLabel">{{ trans('album.edit') }}</h4>
            </div>
            <div class="modal-body">
                <input class="album-name" name="album-name" value="{{ $album->title }}" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('album.popup.close') }}</button>
                <button type="button" class="btn btn-primary edit-album">{{ trans('album.popup.save') }}</button>
            </div>
        </div>
    </div>
</div>
