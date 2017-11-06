@extends('layouts.default')

@section('content')

    @include('user.header', ['currentUser' => $currentUser])

    <div class="row">
        <div class="pull-left">
            <h2>{{ trans('user.list') }}</h2>
        </div>
    </div>

    <div class="row">
        <table class="table table-bordered">
            <tr>
                <td>
                    <div>
                        <a href="{{ route('user.show', $currentUser->id) }}" title="{{ $currentUser->getFullname() }}">
                        @if ($currentUser->avatar_path)
                            <img src="{{ url('/') }}/{{ $currentUser->avatar_path }}" />
                        @else
                            <img src="{{ asset('images/icons/user.png') }} "/>
                        @endif
                        </a>
                    </div>
                    <div><strong>{{ $currentUser->getFullname() }}</strong></div>
                </td>
            </tr>
            <?php foreach ($users as $key => $user): ?>
            <?php if ($user->id === $currentUser->id): continue; endif; ?>
            <tr>
                <td>
                    <div>
                        <a href="{{ route('user.show', $user->id) }}" title="{{ $user->getFullname() }}">
                        @if ($user->avatar_path)
                            <img src="{{ url('/') }}/{{ $user->avatar_path }}" />
                        @else
                            <img src="{{ asset('images/icons/user.png') }} "/>
                        @endif
                        </a>
                    </div>
                    <div>{{ $user->getFullname() }}</div>
                </td>
            </tr>
            <?php endforeach ?>
        </table>
    </div>

    {!! $users->render() !!}

@endsection
