<style type="text/css">
    .gallery-item {
        float: left;
        margin: 0 15px 15px 0;
        width: 80%;
    }
    .current {
        display: block;
    }
</style>
<script>
    lightbox.option({
        'wrapAround': true
    });
</script>

<div class="photos-list">
    @foreach ($photos as $key => $photo)
        <div class="gallery-item">
            <div class="gallery-item-photo">
                <a href="{{ url('/') }}/{{ $photo->path }}" data-lightbox="roadtrip">
                    <img src="{{ url('/') }}/{{ $photo->path_thumb }}"/>
                </a>
                <div>
                    <label>
                        <input class="photo-checkbox" type="checkbox"
                               value="{{ $photo->id }}" style="display: none;">
                    </label>
                </div>
            </div>
            <div class="gallery-item-comments">
                @include('comments.comments_block', ['photo' => $photo])
            </div>
        </div>
    @endforeach
</div>

