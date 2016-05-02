@extends('app')

@section('title') User Not Found @stop

@section('content')
	<h3 align="center">No Game Exists at this URL</h3>
	<p align="center">Head back to the <a href="{{url('game')}}">Lobby</a>?</p>
@stop