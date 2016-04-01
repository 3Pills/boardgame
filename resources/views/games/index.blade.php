@extends('app')

@section('content')

    <h1>memes</h1>
    <hr>
    @foreach ($games as $game)
        {{$game}}
    @endforeach
@stop