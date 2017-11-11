<div class="page-header text-center">
    {{--@if ($user->avatar_path)
        <img src="{{ url('/') }}/{{ $user->avatar_path }}" />
    @endif--}}
    <h1>{{ $user->getFullname() }}</h1>
</div>
