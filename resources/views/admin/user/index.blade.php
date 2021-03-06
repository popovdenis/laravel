@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Users</h2>
                </div>
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-primary" href="{{ route('users.create') }}"> Create New User</a>
                        <a href="#" onclick="goBack()" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.messages')

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th width="280px">Action</th>
            </tr>
            <?php foreach ($users as $key => $user): ?>
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->firstname }}</td>
                <td>{{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a class="btn btn-primary" href="{{ route('users.show', $user->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>
                    <?php if (!$user->isAdmin()): ?>
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['users.destroy', $user->id],
                        'style'=>'display:inline-block'
                    ]) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach ?>
        </table>

        {!! $users->render() !!}
    </div>
@endsection
