<?php $counter = 1 ?>
<table class="table-photos-list" align="center">
    @foreach ($images as $key => $image)
        <?php if ($counter == 8) { ?><tr><?php } ?>
            <td>
                <div style="margin: 0 15px 15px 0">
                    <a href="{{ url('/') }}/{{ $image->path }}"
                       data-lightbox="roadtrip">
                        <img src="{{ url('/') }}/{{ $image->path_thumb }}" />
                    </a>
                </div>
                <div class="checkbox">
                    <label>
                        <input class="remove-photo-checkbox" type="checkbox" value="{{ $image->id }}"
                               style="display: none;">
                    </label>
                </div>
            </td>
            <?php if ($counter == 7) { ?></tr><?php $counter = 0; ?><?php } ?>
        <?php $counter++ ?>
    @endforeach
</table>
