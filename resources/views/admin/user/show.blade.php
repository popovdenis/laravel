@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2> Show Item</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('admin.index') }}"> Back</a>
                </div>
            </div>
        </div>

        <dl>
            <dt>Firstname:</dt>
            <dd>{{ $user->firstname }}</dd>
            <dt>Lastname:</dt>
            <dd>{{ $user->lastname }}</dd>
            <dt>Email:</dt>
            <dd>{{ $user->email }}</dd>
            <dt>Phone:</dt>
            <dd>{{ $user->phone }}</dd>
            <dt>{{ trans('user.date_of_birth')}}</dt>
            <dd>{{ $user->date_of_birth }}</dd>
        </dl>
    </div>

@endsection
