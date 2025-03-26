@extends('layouts.app', ['title' => $title])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-9">
                    <h2>Search Results for {{ $query }}</h2>

                    @php $search_count = 0; @endphp

                    @forelse ($search_results as $result)
                        @if (isset($result->indexable) && $result->indexable instanceof \App\Blog\Models\PostTranslation)
                            @php $search_count++; @endphp
                            <h2>Search result #{{ $search_count }}</h2>
                            @include('blog::partials.index_loop', ['post' => $result->indexable])
                        @else
                            <div class="alert alert-danger">
                                Unable to show this search result – unknown type
                            </div>
                        @endif
                    @empty
                        <div class="alert alert-danger">
                            Sorry, but there were no results!
                        </div>
                    @endforelse
                </div>

                <div class="col-md-3">
                    <h6>Blog Categories</h6>
                    <ul class="binshops-cat-hierarchy">
                        @if ($categories && count($categories))
                            @include('blog::partials._category_partial', [
                                'category_tree' => $categories,
                                'name_chain' => ''
                            ])
                        @else
                            <li><span>No Categories</span></li>
                        @endif
                    </ul>
                </div>
            </div>

            @if (config('blog.search.search_enabled'))
                @include('blog::sitewide.search_form')
            @endif
        </div>
    </div>
@endsection
