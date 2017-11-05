<div class="margin5">
    <span><img src="{{ url('/') }}/{{ $user->avatar_path }}" /></span>
    <span><strong>{{ $user->getFullname() }}</strong></span>
    <span class="pull-right">@include('user.logout')</span>
</div>
