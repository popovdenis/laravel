@extends("layouts.app", ['title' => $title])

@section('blog-custom-css')
    <link type="text/css" href="{{ asset('css/blog.css') }}" rel="stylesheet">
@endsection

@section("content")
    <div class="w-full max-w-7xl mx-auto px-4 py-8">
        @auth
            @if(auth()->user()->canManageBlogPosts())
                <div class="text-center mb-6">
                    <p class="mb-2 text-gray-700">
                        You are logged in as a blog admin user.
                    </p>
                    <a href="{{ route('blog.admin.index') }}"
                       class="inline-flex items-center gap-2 border border-blue-500 text-blue-500 px-4 py-1 text-sm rounded hover:bg-blue-50 transition">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        Go To Blog Admin Panel
                    </a>
                </div>
            @endif
        @endauth

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="lg:w-3/4 w-full">
                @if($category_chain)
                    <div class="mb-4 text-sm text-gray-500">
                        @foreach($category_chain as $cat)
                            / <a href="{{ $cat->categoryTranslations[0]->url($locale) }}" class="text-blue-600 hover:underline">
                                {{ $cat->categoryTranslations[0]['category_name'] }}
                            </a>
                        @endforeach
                    </div>
                @endif

                @if(isset($blog_category) && $blog_category)
                    <h2 class="text-2xl font-semibold text-center text-gray-800">{{ $blog_category->category_name }}</h2>
                    @if($blog_category->category_description)
                        <p class="text-center text-gray-600 mt-2">{{ $blog_category->category_description }}</p>
                    @endif
                @endif

                <div class="grid md:grid-cols-2 gap-6 mt-6">
                    @forelse($posts as $post)
                        @include("blog::partials.index_loop")
                    @empty
                        <div class="col-span-2">
                            <div class="bg-red-100 text-red-700 p-4 rounded">No posts!</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="lg:w-1/4 w-full">
                <h6 class="text-lg font-semibold mb-2">Blog Categories</h6>
                <ul class="space-y-1 text-sm text-gray-700">
                    @if($categories)
                        @include("blog::partials._category_partial", [
                            'category_tree' => $categories,
                            'name_chain' => '',
                            'routeWithoutLocale' => $routeWithoutLocale
                        ])
                    @else
                        <span>No Categories</span>
                    @endif
                </ul>
            </div>
        </div>

        @if(config('blog.search.search_enabled'))
            <div class="mt-8">
                @include('blog::sitewide.search_form')
            </div>
        @endif

        <div class="mt-8 text-center space-x-2">
            @foreach($lang_list as $lang)
                <a href="{{ route('blog.index', $lang->locale) }}"
                   class="text-sm text-blue-600 hover:underline">{{ $lang->name }}</a>
            @endforeach
        </div>
    </div>
@endsection
