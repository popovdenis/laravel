<header class="header">

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header ">
                <a class="navbar-brand" href="#">
                    <img alt="PhotoLab" src="/images/logo.svg" width="62" height="67">
                </a>
            </div>
            @include('user/account.my_account_dropdown')
            @include('user/account.edit_account_popup', ['user' => $currentUser])
            <div class="navbar-addition">
                @if ($currentUser->isAdmin())
                    <a class="nav-button" href="{{ route('admin.index') }}">{{ trans('messages.to_admin') }}</a>
                @endif
                @include('user.logout')
            </div>
        </div>
    </nav>

</header>
@include('user/account.user_info', ['user' => $pageOwner])

@include('user/account.my_comments', ['currentUser' => $currentUser])

<script type="text/javascript">
    $(document).ready(function () {
        userObject.init();
    });
</script>

@include('layouts.messages')
