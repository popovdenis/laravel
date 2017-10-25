<div>
    @foreach ($albums as $key => $album)
       <div style="margin: 0 0 20px;">
            <a style="padding: 5px 15px 5px 15px;"
               class="btn btn-info" href="{{ route('album.show',$album->id) }}">{{ $album->title }}</a>
       </div>
    @endforeach
</div>
{!! $albums->render() !!}
