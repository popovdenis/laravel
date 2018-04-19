<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Семейный ресурс для хранения фотографий</title>

    <link rel="stylesheet" href="{!! asset('css/app.css') !!}">

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="{!! asset('js/bootstrap.js') !!}"></script>
    <link rel="stylesheet" href="{!! asset('css/font-awesome.css') !!}">
    {{--<link rel="stylesheet" href="{!! asset('public/css/style.css') !!}">--}}
    <script type="text/javascript" src="{!! asset('js/album.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/user.js') !!}"></script>

    <link rel="stylesheet" type="text/css" media="all" href="{{asset('css/comments.css')}}" />
    <script type="text/javascript" src="{!! asset('js/comment-reply.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/comment-scripts.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/comment.js') !!}"></script>
</head>
<body>

<div id="app" class="container">
    @yield('content')
</div>
@include('footer')
</body>
</html>
