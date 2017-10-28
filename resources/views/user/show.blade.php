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
            @if ($user->id === $currentUser->id)
                <div class="pull-right">
                    <button class="btn btn-success" data-toggle="modal"
                            data-target="#newAlbum">{{ trans('album.create.new') }}</button>
                </div>
            @endif
        </div>
    </div>

    @include('album.list', array('albums' => $albums, 'count' => 1))
    @include('album.create')

@endsection
