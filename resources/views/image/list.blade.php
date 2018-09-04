<div class="photos-list">
    @foreach ($photos as $key => $photo)
        <div class="gallery-item">
            <div class="gallery-item-photo">
                <a href="#photo-item-{{ $photo->id }}" data-fancybox="gallery" class="thumbnail">
                    <img src="{{ url('/') }}/{{ $photo->path_thumb }}"/>
                </a>
                <div class="photo-item-comments" style="display: none" id="photo-item-{{ $photo->id }}">
                    <img src="{{ url('/') }}/{{ $photo->path }}" alt="">
                    <div class="gallery-item-comments" style="display: none">
                        @include('comments.comments_block', ['photo' => $photo])
                    </div>
                </div>
                <input class="photo-checkbox" type="checkbox" value="{{ $photo->id }}" style="display: none;">
            </div>            
        </div>
    @endforeach
</div>


<script>

    // Initialise fancybox with custom settings
    $('[data-fancybox="gallery"]').fancybox({

        // Disable idle
        idleTime : 0,

        // Display only these two buttons
        buttons : [
            'info', 'close'
        ],

        // Custom caption
        caption : function( instance, obj ) {
            console.log($(this));
            let commentsForm = $(this).next().find('.gallery-item-comments');
            return commentsForm.html();
        },

        onInit: function(instance) {

            // Toggle caption on tap
            instance.$refs.container.on('touchstart', '[data-fancybox-info]', function(e) {
                e.stopPropagation();
                e.preventDefault();

                instance.$refs.container.toggleClass('fancybox-vertical-caption');
            });

            // Display caption on button hover
            instance.$refs.container.on('mouseenter', '[data-fancybox-info]', function(e) {
                instance.$refs.container.addClass('fancybox-vertical-caption');

                // Hide caption when mouse leaves caption area
                instance.$refs.caption.one('mouseleave', function(e) {
                    instance.$refs.container.removeClass('fancybox-vertical-caption');
                });

            });

        }

    });
</script>
