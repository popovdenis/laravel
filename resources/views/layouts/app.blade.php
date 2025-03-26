<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Blog' }}</title>
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
    @yield('blog-custom-css')
</head>
<body>
<div class="container">
    @yield('content')
</div>
</body>
</html>
