@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ trans('user.list') }}</h2>
            </div>
            <div class="pull-right">
                @include('user.logout')
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        @foreach ($users as $key => $user)
            <tr>
                <td>
                    <div>
                        <a href="{{ route('user.show',$user->id) }}" title="{{ $user->getFullname() }}">
                            <img src="{{ asset('images/icons/user.png') }} "/>
                        </a>
                    </div>
                    <div>{{ $user->getFullname() }}</div>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $users->render() !!}

@endsection
