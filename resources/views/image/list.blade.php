<style type="text/css">
    .gallery-item {
        float: left;
        margin: 0 15px 15px 0;
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

<div class="gallery">
    <div class="gallery-inner">
        @foreach ($photos as $key => $photo)
            <div class="gallery-item">
                <div class="gallery-item-photo">
                    <a href="{{ url('/') }}/{{ $photo->path }}" data-lightbox="roadtrip">
                        <img src="{{ url('/') }}/{{ $photo->path_thumb }}"/>
                    </a>
                    <div class="checkbox">
                        <label>
                            <input class="remove-photo-checkbox" type="checkbox"
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
</div>

