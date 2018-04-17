@extends('layouts.default')

@section('content')

    @include('user.header', ['currentUser' => $currentUser])

    <h2 class="page-subtitle">{{ trans('user.list') }}</h2>


    <ul class="users-list">
        <li class="current-user">
            <div>
                <a href="{{ route('user.show', $currentUser->id) }}" title="{{ $currentUser->getFullname() }}">
                    @if ($currentUser->avatar_path)
                        <img src="{{ url('/') }}/{{ $currentUser->avatar_path }}" />
                    @else
                        <img src="{{ asset('public/images/icons/user.png') }} "/>
                    @endif
                </a>
            </div>
            <div><strong>{{ $currentUser->getFullname() }}</strong></div>
        </li>
        <?php foreach ($users as $key => $user): ?>
        <?php if ($user->id === $currentUser->id): continue; endif; ?>
        <li>
            <div>
                <a href="{{ route('user.show', $user->id) }}" title="{{ $user->getFullname() }}">
                    @if ($user->avatar_path)
                        <img src="{{ url('/') }}/{{ $user->avatar_path }}" />
                    @else
                        <img src="{{ url('/') }}/images/icons/user.png"/>
                    @endif
                </a>
            </div>
            <div>{{ $user->getFullname() }}</div>
        </li>
        <?php endforeach ?>
    </ul>
    {!! $users->render() !!}

@endsection
