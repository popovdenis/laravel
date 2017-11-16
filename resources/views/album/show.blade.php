@extends('layouts.default')

@section('content')
    <script type="text/javascript" src="{!! asset('public/js/album.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('public/js/photo.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('public/js/dropzone.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('public/js/responsive.js') !!}"></script>
    {{--<script type="text/javascript" src="{!! asset('public/js/lightbox.js') !!}"></script>--}}

    <link rel="stylesheet" type="text/css" media="all" href="{!! asset('public/css/dropzone.css') !!}">
    <link rel="stylesheet" type="text/css" media="all" href="{{asset('public/css/lightbox.css')}}"/>

    <input type="hidden" name="_token" value="<?php echo csrf_token() ?>"/>

    @include('user.header', ['currentUser' => $currentUser])

    <div class="clearfix">
        <div class="btn-group">
            <?php if ($album->owner()->id === $currentUser->id): ?>

            <button data-toggle="dropdown" class="btn btn-primary">{{ trans('album.edit') }}
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a data-toggle="modal" data-target="#editAlbumModal">{{ trans('album.edit.name') }}</a></li>
                <li><a class="album-add-photo-popup" data-toggle="modal"
                       data-target="#uploadPhotoModal">{{ trans('photo.add') }}</a></li>
                <li>
                    <a class="remove-photos-btn">{{ trans('photo.delete') }}</a>
                    <a class="cancel-photos-btn" style="display: none;">{{ trans('photo.cancel') }}</a>
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
            <?php endif; ?>
            <button class="btn btn-primary download-photos-btn">{{ trans('photo.download') }}</button>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary"
               href="{{ route('user.show', $album->owner()->id) }}">{{ trans('messages.back') }}
            </a>
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

            commentObject.getComentsModalName = '#newCommentsModal';
            commentObject.getNewCommentsUrl = "{{ url('/') }}" + '/comment/getNewComments';
            commentObject.init();
        });
    </script>
@endsection
