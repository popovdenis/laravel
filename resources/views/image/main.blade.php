<div class="remove-photos-all-block" style="margin: 5px;display: none;">
    <button class="btn btn-danger btn-remove-photos-all">{{ trans('album.photo.remove') }}</button>
    <input type="hidden" name="album-id" value="{{ $album->id }}" />
</div>

<?php if (!empty($photos)): ?>
@include('image.list', ['photos' => $photos, 'counter' => 1])
<?php endif; ?>
