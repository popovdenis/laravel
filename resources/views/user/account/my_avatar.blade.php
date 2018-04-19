<div id ="avatar" class="btn-group" style="border:1px solid red;">
    <a href="#" class="nav-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @if (Auth::user()->avatar_path)
        <img src="{{ url('/') }}/{{ Auth::user()->avatar_path }}" width="50"/>
        @else
        <img src="{{ asset('public/images/icons/user.png') }} "/>
        @endif
    </a>
    <ul class="dropdown-menu">
        <li><a data-toggle="modal" data-target="#editUserModal">{{ trans('user.edit.account') }}</a></li>
    </ul>

    <span style="padding-left: 10px;">
        {{ Auth::user()->lastname }}<br>{{ Auth::user()->firstname }}
    </span>
</div>
