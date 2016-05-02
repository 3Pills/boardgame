@extends('app')

@section('title') List of Users @stop

@section('content')
<h3>Registered Users</h3>
@foreach (\App\User::all()->sortBy('name') as $key=>$user)
	@if($key % 3 == 2) 
	<div class="row" style="margin:0px;"> 
	@endif
	<div class="col-sm-4" style="min-width:128px;padding:2px;">
		<a href="{{'user/'.$user->url}}" class="">
			<div class="panel panel-default panel-body">
				<img src="{{ url(file_exists('./assets/images/avatars/'.$user->url.'.jpg') ? './assets/images/avatars/'.$user->url.'.jpg' : './assets/images/default-avatar.png') }}" alt="avatar" class="profile-avatar" style="float:left;width:34px;height:34px;margin-right:8px;"/>
		    	<div>
		        	<p>{{$user->name}}</p>
		        </div>
		    </div>
	    </a>
	</div>
	@if($key % 3 == 1) 
	</div> 
	@endif
@endforeach
@stop