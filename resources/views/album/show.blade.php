@extends('layouts.default')

@section('content')
    <script type="text/javascript" src="{!! asset('js/album.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/photo.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/dropzone.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/lightbox.js') !!}"></script>

    <link rel="stylesheet" type="text/css" media="all" href="{!! asset('css/dropzone.css') !!}">
    <link rel="stylesheet" type="text/css" media="all" href="{{asset('css/lightbox.css')}}" />

    <input type="hidden" name="_token" value="<?php echo csrf_token() ?>" />

    <div>
        @include('user/account.my_account')
        <?php if ($album->owner()->id === $currentUser->id): ?>
            <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-primary">{{ trans('album.edit') }}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a data-toggle="modal" data-target="#editAlbumModal">{{ trans('album.edit.name') }}</a></li>
                    <li><a class="album-add-photo-popup"
                            data-toggle="modal" data-target="#uploadPhotoModal">{{ trans('album.add.photo') }}</a></li>
                    <li>
                        <a class="remove-photos-link">{{ trans('album.delete.photo') }}</a>
                        <a class="cancel-remove-photos-link" style="display: none;">{{ trans('album.cancel') }}</a>
                    </li>
                    <li><a class="remove-album-link">
                        {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['album.destroy', $album->id],
                        'style'=>'display:inline'
                    ]) !!}
                        {!! Form::submit(trans('album.delete'), ['class'=>'remove-album-btn']) !!}
                        {!! Form::close() !!}
                    </a></li>
                </ul>
            </div>
        <?php endif; ?>
        <span>
            <a href="{{ url('/album/download', $album->id) }}"
               class="btn btn-warning">{{ trans('album.download') }}</a>
        </span>
        <div class="pull-right">
            @include('user/account.my_comments', ['user' => $currentUser])
            <a class="btn btn-primary"
               href="{{ route('user.show', $album->owner()->id) }}">{{ trans('messages.back') }}
            </a>
            @include('user.logout')
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $album->title }}</h2>
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

    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    @include('image.main', ['photos' => $photos])

    @include('album.edit_modal')
    @include('album.upload_photos_modal')

    <script type="text/javascript">
        $(document).ready(function () {
            albumObject.saveAlbumUrl = "{{ route('album.store') }}";
            albumObject.updateAlbumUrl = "{{ route('album.update', $album->id) }}";
            albumObject.uploadFilesUrl = "{{ url('/') }}" + '/image/uploadFiles';
            albumObject.uploadPhotoAlbumUrl = "{{ route('image.store') }}";
            albumObject.init();
            albumObject.initDropzone();

            photoObject.removePhotosUrl = "{{ url('/') }}" + "/image/removePhotos";
            photoObject.init();
        });
    </script>
@endsection
