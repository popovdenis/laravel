@foreach(['success', 'error'] as $type)
    @if ($message = Session::get($type))
        <?php $css = ($type == 'error') ? 'danger' : 'success' ?>
        <div class="alert alert-<?php echo $css ?>">
            <p>{{ $message }}</p>
        </div>
    @endif
@endforeach
