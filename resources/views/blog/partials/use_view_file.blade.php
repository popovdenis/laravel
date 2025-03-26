@if(\View::exists($post->full_view_file_path()))
    @include("custom_blog_posts." . $post->use_view_file, ['post' =>$post])
@else
    @if(\Auth::check() && \Auth::user()->canManageBlogPosts())
        <div class='alert alert-danger'>Custom blog post blade view file
                        (<code>{{$post->full_view_file_path()}}</code>) not found. <a
                    href='https://github.com/binshops/laravel-blog'
                    target='_blank'>See Laravel Blog Package help here</a>.
                    </div>
    @else
        <div class='alert alert-danger'>Sorry, but there is an error showing that blog post. Please come back later.</div>
    @endif
@endif
