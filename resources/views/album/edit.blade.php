@extends('layouts.album')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Album</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('album.index') }}"> Back</a>
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

    {!! Form::model($album, [
        'method' => 'PATCH',
        'route' => ['album.update', $album->id],
        'enctype' => "multipart/form-data"
    ]) !!}
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Title:</strong>
                {!! Form::text(
                    'title',
                    null,
                    array('placeholder' => 'Title','class' => 'form-control'))
                !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Avatar image:</strong>
                {!! Form::file(
                    'file',
                    null,
                    array('placeholder' => 'Avatar image','class' => 'form-control', 'multiple' => 'multiple'))
                 !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </div>
    {!! Form::close() !!}

@endsection
