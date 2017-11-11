<div class="photos-list">
    @foreach ($photos as $key => $photo)
        <div class="gallery-item">
            <div class="gallery-item-photo">
                <a href="" data-lightbox-target="#photo-item-{{ $photo->id }}" data-lightbox-fit-viewport="false" class="thumbnail">
                    <img src="{{ url('/') }}/{{ $photo->path_thumb }}"/>
                </a>
                <div class="hidden" id="photo-item-{{ $photo->id }}">
                    <input class="photo-checkbox" type="checkbox" value="{{ $photo->id }}" style="display: none;">
                    <img src="{{ url('/') }}/{{ $photo->path }}" alt="">
                    <div class="gallery-item-comments">
                        @include('comments.comments_block', ['photo' => $photo])
                    </div>
                </div>
            </div>            
        </div>
    @endforeach
</div>

