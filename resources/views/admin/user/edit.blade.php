@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit User</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('admin.index') }}"> Back</a>
                </div>
            </div>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::model(
            $user,
            ['method' => 'PATCH','route' => ['users.update', $user->id], 'enctype' => "multipart/form-data"]
        ) !!}
        <div class="form-group">
            <strong>{{ trans('user.firstname') }}:</strong>
            {!! Form::text(
                'firstname',
                null,
                array('placeholder' => trans('user.firstname'),'class' => 'form-control'))
            !!}
        </div>
        <div class="form-group">
            <strong>{{ trans('user.lastname') }}:</strong>
            {!! Form::text(
                'lastname',
                null,
                array('placeholder' => trans('user.lastname'),'class' => 'form-control'))
            !!}
        </div>
        <div class="form-group">
            <strong>{{ trans('user.email') }}:</strong>
            {!! Form::text(
                'email',
                null,
                array('placeholder' => trans('user.email'),'class' => 'form-control'))
            !!}
        </div>
        <div class="form-group">
            <strong>{{ trans('user.password') }}:</strong>
            {!! Form::password(
                'password',
                null,
                array('placeholder' => trans('user.password'),'class' => 'form-control'))
             !!}
        </div>
        <div class="form-group">
            <strong>{{ trans('user.avatar') }}:</strong>
            @if ($user->avatar_path)
                <div>
                    <img src="{{ url('/') }}/{{ $user->avatar_path }}"/>
                </div>
            @endif
            {!! Form::file(
                'file',
                null,
                array('placeholder' => trans('user.avatar'), 'class' => 'form-control'))
             !!}
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        {!! Form::close() !!}
    </div>

@endsection
