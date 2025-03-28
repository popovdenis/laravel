@if(\View::exists($post->fullViewFilePath()))
    @include("custom_blog_posts." . $post->use_view_file, ['post' => $post])
@endif
