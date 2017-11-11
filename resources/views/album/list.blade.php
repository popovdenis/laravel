<div class="albums-list">
    @foreach ($albums as $key => $album)
        <li>
            <a href="{{ route('album.show',$album->id) }}">
                {{ $album->title }}
                <input class="album-checkbox" type="checkbox" value="{{ $album->id }}" style="display: none;">
            </a>
        </li>
    @endforeach
</div>
{!! $albums->render() !!}
