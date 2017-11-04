@extends('layouts.default')

@section('content')
    <script type="text/javascript" src="{!! asset('js/album.js') !!}"></script>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $user->getFullname() }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('user.index') }}">{{ trans('messages.back') }}</a>
                @include('user.logout')
            </div>
        </div>
    </div>

    @include('album.main', ['albums' => $albums, 'user' => $user, 'currentUser' => $currentUser])

    <script type="text/javascript">
        $(document).ready(function () {
            albumObject.saveAlbumUrl = "{{ route('album.store') }}";
            albumObject.uploadFilesUrl = "{{ url('/') }}" + '/image/uploadFiles';
            albumObject.uploadPhotoAlbumUrl = "{{ route('image.store') }}";
            albumObject.removeAlbumsUrl = "{{ url('/album/removeList') }}";
            albumObject.downloadAlbumsUrl = "{{ url('/album/download') }}";
            albumObject.init();
        });
    </script>
@endsection
