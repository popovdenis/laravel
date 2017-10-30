<div class="modal fade" id="newAlbum" tabindex="-1" role="dialog"
     aria-labelledby="newAlbumLabel" aria-hidden="true" style="display: none;">

    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'album.store','method'=>'POST', 'id' => 'albumStoreForm']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Create New Album</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {!! Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-save-album">Save changes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
