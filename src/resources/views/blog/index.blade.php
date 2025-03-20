<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
</head>
<body>
    <h1>Blog</h1>
    @foreach ($posts as $post)
        <h2>{{ $post->title }}</h2>
        <a href="{{ route('blog.show', $post->id) }}">Read more</a>
    @endforeach
</body>
</html>
