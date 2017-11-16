<div class="modal fade" id="newAlbum" tabindex="-1" role="dialog"
     aria-labelledby="newAlbumLabel" aria-hidden="true" style="display: none;">

    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'album.store','method'=>'POST', 'id' => 'albumStoreForm']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('album.create') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>{{ trans('album.title') }}</strong>
                            {!! Form::text(
                                    'title',
                                    null,
                                    ['placeholder' => trans('album.placeholder.title'), 'class' => 'form-control'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-primary" data-dismiss="modal">{{ trans('album.popup.close') }}</button>
                <button type="button" class="btn btn-primary btn-save-album">{{ trans('album.popup.save') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
