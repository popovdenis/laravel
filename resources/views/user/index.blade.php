@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ trans('user.list') }}</h2>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th>{{ trans('user.firstname') }}</th>
            <th>{{ trans('user.lastname') }}</th>
            <th>{{ trans('user.email') }}</th>
            <th width="280px">{{ trans('user.action') }}</th>
        </tr>
        @foreach ($users as $key => $user)
            <tr>
                <td>{{ $user->firstname }}</td>
                <td>{{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('user.show',$user->id) }}">{{ trans('user.show') }}</a>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $users->render() !!}

@endsection
