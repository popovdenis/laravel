<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"
     aria-labelledby="editUserModal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('user.edit.account') }}</h4>
            </div>
            <div class="modal-body">
                {!! Form::model($user,
                    [
                        'id' => 'update-user-form',
                        'method' => 'PATCH',
                        'route' => ['user.update', $user->id],
                        'enctype' => "multipart/form-data"
                    ]
                ) !!}
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            {{ Form::label('firstname', trans('user.firstname') . ':') }}
                            {!! Form::text(
                                'firstname',
                                null,
                                array('placeholder' => trans('user.firstname'),'class' => 'form-control'))
                            !!}
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            {{ Form::label('lastname', trans('user.lastname') . ':') }}
                            {!! Form::text(
                                'lastname',
                                null,
                                array('placeholder' => trans('user.lastname'),'class' => 'form-control'))
                            !!}
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            {{ Form::label('email', trans('user.email') . ':') }}
                            {!! Form::text(
                                'email',
                                null,
                                array('placeholder' => trans('user.email'),'class' => 'form-control'))
                            !!}
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            {{ Form::label('password', trans('user.password') . ':') }}
                            {{ Form::password('password', array('class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <strong>{{ trans('user.avatar') }}:</strong>
                            @if ($user->avatar_path)
                                <div>
                                    <img src="{{ url('/') }}/{{ $user->avatar_path }}" />
                                </div>
                            @endif
                            {!! Form::file(
                                'file',
                                null,
                                array('placeholder' => trans('user.avatar'), 'class' => 'form-control'))
                             !!}
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <strong>{{ trans('user.phone') }}:</strong>
                            {!! Form::text(
                                'phone',
                                null,
                                array('placeholder' => trans('user.phone'),'class' => 'form-control'))
                            !!}
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <strong>{{ trans('user.date_of_birth') }}:</strong>
                            {!! Form::text(
                                'date_of_birth',
                                null,
                                array('placeholder' => trans('user.date_of_birth'),'class' => 'form-control'))
                            !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('album.popup.close') }}</button>
                <button type="button"
                        class="btn btn-primary update-user-btn">{{ trans('album.popup.save') }}</button>
            </div>
        </div>
    </div>
</div>
