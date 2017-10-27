@extends('layouts.default')

@section('content')

    <script type="text/javascript" src="{!! asset('js/dropzone.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/album.js') !!}"></script>
    <link rel="stylesheet" href="{!! asset('css/dropzone.css') !!}">

    <link rel="stylesheet" type="text/css" media="all" href="{{asset('css/lightbox.css')}}" />
    <script type="text/javascript" src="{!! asset('js/lightbox.js') !!}"></script>

    <link rel="stylesheet" type="text/css" media="all" href="{{asset('css/comments.css')}}" />
    <script type="text/javascript" src="{!! asset('js/comment-reply.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/comment-scripts.js') !!}"></script>

    <div>
        @if ($owner->id === $currentUser->id)
            <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-primary">{{ trans('album.edit') }}<span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a data-toggle="modal" data-target="#editAlbumModal">{{ trans('album.edit.name') }}</a></li>
                    <li><a data-toggle="modal" data-target="#uploadPhotoModal">{{ trans('album.add.photo') }}</a></li>
                    <li>
                        <a class="remove-photos-link">{{ trans('album.delete.photo') }}</a>
                        <a class="cancel-remove-photos-link" style="display: none;">{{ trans('album.cancel') }}</a>
                    </li>
                    <li><a>
                            {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['album.destroy', $album->id],
                            'style'=>'display:inline'
                        ]) !!}
                            {!! Form::submit(trans('album.delete')) !!}
                            {!! Form::close() !!}
                        </a></li>
                </ul>
            </div>
        @endif
        <span>
            <a href="{{ url('/album/download', $album->id) }}"
               class="btn btn-warning">{{ trans('album.download') }}</a>
        </span>
        <div class="pull-right">
            @include('user.logout')
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $album->title }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary"
                   href="{{ route('user.show', $album->owner()->id) }}">{{ trans('messages.back') }}</a>
            </div>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="remove-photos-all-block" style="margin: 5px;display: none;">
        <button class="btn btn-danger btn-remove-photos-all">{{ trans('album.photo.remove') }}</button>
        <input type="hidden" name="_token" value="<?php echo csrf_token() ?>" />
        <input type="hidden" name="album-id" value="{{ $album->id }}" />
    </div>

    <?php if (!empty($photos)): ?>
    @include('image.list', ['photos' => $photos, 'counter' => 1])
    <?php endif; ?>

    @include('album.edit_modal')
    @include('album.upload_photos_modal')

    <script type="text/javascript">
        $(document).ready(function () {
            albumObject.updateAlbumUrl = "{{ route('album.update', $album->id) }}";
            albumObject.uploadFilesUrl = "{{ url('/') }}" + '/image/uploadFiles';
            albumObject.uploadPhotoAlbumUrl = "{{ route('image.store') }}";
            albumObject.removePhotosUrl = "{{ url('/') }}" + "/image/removePhotos";

            albumObject.init();
        });
    </script>
@endsection
