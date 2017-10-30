@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ trans('user.info') }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('user.index') }}">{{ trans('messages.back') }}</a>
                @include('user.logout')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ trans('user.fullname') }}:</strong>
                {{ $user->getFullname() }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ trans('album.albums') }}</h2>
            </div>
            <div class="pull-right">
            @if ($user->id === $currentUser->id)
                <button class="btn btn-success"
                        data-toggle="modal" data-target="#newAlbum">{{ trans('album.create') }}</button>
            @endif
                <button class="btn btn-warning">{{ trans('album.download') }}</button>
                <button class="btn btn-danger">{{ trans('album.delete') }}</button>
            </div>
        </div>
    </div>

    @include('album.create')
    @include('album.list', array('albums' => $albums, 'count' => 1))

@endsection
