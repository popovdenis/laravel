<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{ trans('album.albums') }}</h2>
        </div>
        <div class="pull-right">
            @if ($user->id === $currentUser->id)
                <button class="btn btn-success"
                        data-toggle="modal" data-target="#newAlbum">{{ trans('album.create') }}</button>
            @endif
            @if ($albums->count())
                <button class="btn btn-warning album-download">{{ trans('album.download') }}</button>
                <button class="btn btn-danger remove-albums-btn">{{ trans('album.delete') }}</button>
                <button class="btn btn-danger cancel-albums-btn" style="display: none;">{{ trans('album.cancel') }}</button>
            @endif
        </div>
    </div>
</div>

@include('album.create_modal')

@if ($albums->count())
    <button class="btn btn-danger btn-xs delete-selected-albums"
            style="margin-bottom: 15px; display: none;">{{ trans('album.delete.selected') }}</button>

    @include('album.list', array('albums' => $albums, 'count' => 1))
@endif
