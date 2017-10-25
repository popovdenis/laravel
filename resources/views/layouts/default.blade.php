<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Site</title>

    <script type="text/javascript" src="{!! asset('js/jquery-1.11.1.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/bootstrap.js') !!}"></script>
    <link rel="stylesheet" href="{!! asset('css/bootstrap.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/font-awesome.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/style.css') !!}">
</head>
<body>

<div class="container">
    @yield('content')
</div>

</body>
</html>
