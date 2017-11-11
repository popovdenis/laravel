
<div class="clearfix">
    <h2 class="page-subtitle pull-left">{{ trans('album.albums') }}</h2>

    <div class="album-actions btn-group pull-right" role="group" aria-label="...">
        @if ($user->id === $currentUser->id)
            <button class="btn btn-success"
                    data-toggle="modal" data-target="#newAlbum">{{ trans('album.create') }}</button>
        @endif

        @if ($albums->count())
            <button class="btn btn-warning download-albums-btn">{{ trans('album.download') }}</button>
            @if ($user->id === $currentUser->id)
                <button class="btn btn-danger remove-albums-btn">{{ trans('album.delete') }}</button>
            @endif
            {{--<button class="btn btn-danger cancel-albums-btn" style="display: none;">{{ trans('album.cancel') }}</button>--}}
        @endif
    </div>
</div>






@if ($albums->count())
    @if ($user->id === $currentUser->id)
        <button class="btn btn-danger btn-xs delete-selected-albums"
                style="margin-bottom: 15px; display: none;">{{ trans('album.delete.selected') }}</button>
    @endif
    <button class="btn btn-warning btn-xs download-selected-albums"
            style="margin-bottom: 15px; display: none;">{{ trans('album.download.selected') }}</button>

    @include('album.list', array('albums' => $albums, 'count' => 1))
@endif

@include('album.create_modal')
