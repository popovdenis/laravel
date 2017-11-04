<?php if (!empty($photos)): ?>
<button class="btn btn-danger btn-xs delete-selected-photos"
        style="margin-bottom: 15px; display: none;">{{ trans('album.photo.remove') }}</button>
<?php endif; ?>
<button class="btn btn-warning btn-xs download-selected-photos"
        style="margin-bottom: 15px; display: none;">{{ trans('photo.download.selected') }}</button>
<input type="hidden" name="album-id" value="{{ $album->id }}" />

<?php if (!empty($photos)): ?>
@include('image.list', ['photos' => $photos, 'counter' => 1])
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        photoObject.removePhotosUrl = "{{ url('/') }}" + "/image/remove";
        photoObject.downloadPhotosUrl = "{{ url('/image/download') }}";
        photoObject.init();
    });
</script>
