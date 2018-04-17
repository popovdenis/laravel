<div class="photos-list">
    @foreach ($photos as $key => $photo)
        <div class="gallery-item">
            <div class="gallery-item-photo">
                <a href="" data-lightbox-target="#photo-item-{{ $photo->id }}" data-lightbox-fit-viewport="false" class="thumbnail" data-lightbox-group="thumbnail">
                    <img src="{{ url('/') }}/{{ $photo->path_thumb }}"/>
                </a>
                <input class="photo-checkbox" type="checkbox" value="{{ $photo->id }}" style="display: none;">
                <div class="hidden" id="photo-item-{{ $photo->id }}">
                    <img src="{{ url('/') }}/{{ $photo->path }}" alt="">
                    <div class="gallery-item-comments">
                        @include('comments.comments_block', ['photo' => $photo])
                    </div>
                </div>
            </div>            
        </div>
    @endforeach
</div>

