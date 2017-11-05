<div class="row">
    @include('user/account.user_info', ['user' => $currentUser])
</div>
<div class="row">
    @include('user/account.my_account_dropdown')
    @include('user/account.edit_account_popup', ['user' => $currentUser])

    <div class="pull-right">
        @include('user/account.my_comments', ['user' => $currentUser])
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        userObject.init();
    });
</script>

@if ($message = Session::get('success'))
<div class="row">
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
</div>
@endif
