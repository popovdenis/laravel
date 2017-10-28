<style type="text/css">
    /*.gallery {*/
    /*width: 100%;*/
    /*height: 100%*/
    /*}*/
    /*.gallery-inner {*/
    /*position: absolute;*/
    /*left: 0;*/
    /*top: 0;*/
    /*white-space: nowrap;*/
    /*}*/
    .gallery-item {
        float: left;
        margin: 0 15px 15px 0;
    }
    .gallery-item-comments {
        /*display: none;*/
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
                </div>
                <div class="gallery-item-comments">
                    @include('comments.comments_block', ['photo' => $photo])
                </div>
                <div class="checkbox">
                    <label>
                        <input class="remove-photo-checkbox" type="checkbox" value="{{ $photo->id }}" style="display: none;">
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>

