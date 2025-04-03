<div>
    @foreach ($blocks as $block)
        @includeIf('blog.blocks.' . $block['type'], ['data' => $block['data'] ?? []])
    @endforeach
</div>
