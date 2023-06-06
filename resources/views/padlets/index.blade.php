<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
<ul>
    @foreach($padlets as $padlet)
        <li><h3>{{$padlet->title}}</h3>
            <a href="padlets/{{$padlet->id}}">{{$padlet->title}}</a>
        </li>
    @endforeach
</ul>

</body>
</html>
