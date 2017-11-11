@extends('layouts.default')

@section('content')
    @include('user.header', ['currentUser' => $currentUser, 'pageOwner' => $pageOwner])

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
