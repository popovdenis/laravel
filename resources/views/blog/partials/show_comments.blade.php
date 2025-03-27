@php
    $commentType = config('blog.comments.type_of_comments_to_show', 'built_in');
@endphp

@if ($commentType === 'built_in')
    @include("blog::partials.built_in_comments")
    @include("blog::partials.add_comment_form")
@elseif ($commentType === 'disqus')
    @include("blog::partials.disqus_comments")
@elseif ($commentType === 'custom')
    @include('blog::partials.custom_comments')
@elseif ($commentType === 'disabled')
    {{-- Comments disabled --}}
@else
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        Invalid <code>type_of_comments_to_show</code> config option
    </div>
@endif
