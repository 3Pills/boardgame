@extends('app')

@section('content')
    <h1>Users</h1>
    <hr>
    @foreach ($users as $user)
    	<div class="row">
	        <div class="col-sm-3">{{ $user->name }}</div>
	        <div class="col-sm-3">{{ $user->password }}</div>
	        <div class="col-sm-3">{{ $user->email }}</div>
	    </div>
    @endforeach
@stop