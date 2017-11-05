<div class="margin5">
    @if ($user->avatar_path)
        <span><img src="{{ url('/') }}/{{ $user->avatar_path }}" /></span>
    @endif
    <span><strong>{{ $user->getFullname() }}</strong></span>
    <span class="pull-right">@include('user.logout')</span>
</div>
