@extends('app')

@section('title') Welcome! @stop

@section('includes')
    <script type="text/javascript">
        function memes() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("meme").innerHTML = xhttp.responseText;
                }
            }
            xhttp.open("GET", "robots.txt", true)
            xhttp.send()
        }
    </script>
@stop

@section('content')
    <h1>Welcome to the website!</h1>
    <p>This site will eventually host a pretty good JavaScript-based multiplayer game. For now just register an account and trust me on this one. It's gonna be pretty real tbh.</p>
@stop