<div class="panel-body">
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"
         aria-labelledby="editUserModal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('user.edit.account') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="alert response-message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ trans('album.popup.close') }}</button>
                    <button type="button"
                            class="btn btn-primary update-user">{{ trans('album.popup.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>