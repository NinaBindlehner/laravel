<!--Werte von Route web.php anzeigen-->
<body>
<ul>
    @foreach ($padlets as $padlet)
        <li>{{$padlet->title}} {{$padlet->description}}</li>
    @endforeach
</ul>
</body>
