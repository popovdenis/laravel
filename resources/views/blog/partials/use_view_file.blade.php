@if(\View::exists($post->full_view_file_path()))
    @include("custom_blog_posts." . $post->use_view_file, ['post' => $post])
@endif
