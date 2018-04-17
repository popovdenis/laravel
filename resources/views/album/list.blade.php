<div class="albums-list">
    @foreach ($albums as $key => $album)
        <li>
            <a href="{{ route('album.show', $album->id) }}" @if ($album->getLogo()) style="background: url('{{ url('/') }}/{{ $album->getLogo() }}') no-repeat 50% 50%; -webkit-background-size: cover;background-size: cover; "  @endif >
                {{--<img src="{{ url('/') }}/{{ $album->getLogo() }}" alt="" />--}}
                <input class="album-checkbox" type="checkbox" value="{{ $album->id }}" style="display: none;">
            </a>
        </li>
    @endforeach
</div>
{!! $albums->render() !!}
