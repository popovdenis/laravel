<div class="albums-list">
    @foreach ($albums as $key => $album)
        <li>
            <a href="{{ route('album.show',$album->id) }}">
                {{ $album->title }}
                <input class="album-checkbox hidden" type="checkbox" value="{{ $album->id }}">
            </a>
        </li>
    @endforeach
</div>
{!! $albums->render() !!}
