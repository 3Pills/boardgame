@extends('app')

@section('title') Welcome! @stop

@section('includes')
<script type="text/javascript">
    function memes() {
        $.get({
            url: "{{url('robots.txt')}}",
            success: function(data) { document.getElementById("meme").innerHTML = data; }
        });
    }
</script>
@stop

@section('content')

<h1>Welcome to the website!</h1>
<p>This site will eventually host a pretty good JavaScript-based multiplayer game. For now just register an account and trust me on this one. It's gonna be pretty real tbh.</p>
@stop