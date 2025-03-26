{{--This is only included for backwards compatibility. It will be removed at a future stage.--}}
@if (config('blog.search.search_enabled') )
    @include('binshopsblog::sitewide.search_form')
@endif
