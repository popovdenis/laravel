@extends("layouts.app",['title'=>$title])

@section('blog-custom-css')
    <link type="text/css" href="{{ asset('-blog.css') }}" rel="stylesheet">
@endsection

@section("content")

    <div class='col-sm-12 blog_container'>
        @if(\Auth::check() && \Auth::user()->canManageBlogPosts())
            <div class="text-center">
                <p class='mb-1'>You are logged in as a blog admin user.
                    <br>
                    <a href='{{route("blog.admin.index")}}'
                       class='btn border  btn-outline-primary btn-sm '>
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        Go To Blog Admin Panel</a>
                </p>
            </div>
        @endif

        <div class="row">
            <div class="col-md-9">

                @if($category_chain)
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                @forelse($category_chain as $cat)
                                    / <a href="{{$cat->categoryTranslations[0]->url($locale)}}">
                                        <span class="cat1">{{$cat->categoryTranslations[0]['category_name']}}</span>
                                    </a>
                                @empty @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($blog_category) && $blog_category)
                    <h2 class='text-center'> {{$blog_category->category_name}}</h2>

                    @if($blog_category->category_description)
                        <p class='text-center'>{{$blog_category->category_description}}</p>
                    @endif

                @endif

                <div class="container">
                    <div class="row">
                        @forelse($posts as $post)
                            @include("blog::partials.index_loop")
                        @empty
                            <div class="col-md-12">
                                <div class='alert alert-danger'>No posts!</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <h6>Blog Categories</h6>
                <ul class="-cat-hierarchy">
                    @if($categories)
                        @include("blog::partials._category_partial", [
    'category_tree' => $categories,
    'name_chain' => $nameChain = "",
    'routeWithoutLocale' => $routeWithoutLocale
    ])
                    @else
                        <span>No Categories</span>
                    @endif
                </ul>
            </div>
        </div>

        @if (config('blog.search.search_enabled') )
            @include('blog::sitewide.search_form')
        @endif
        <div class="row">
            <div class="col-md-12 text-center">
                @foreach($lang_list as $lang)
                    <a href="{{route("blog.index" , $lang->locale)}}">
                        <span>{{$lang->name}}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection
