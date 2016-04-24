@extends('app')

@section('title') User Not Found @stop

@section('content')
	<h3 align="center">No Game Exists With at this URL</h3>
	<p align="center">Head back to the {!! HTML::link(URL::to('game'), "Lobby") !!}?</p>
@stop