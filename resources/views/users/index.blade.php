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
		        	<p>
						<?php
							$last_activity = -1;

							$session = \App\Sessions::where('user_id', '=', $user->id)->first();
							if ($session) {
								$last_activity = $session->last_activity;
							}

							$carbonVer = \Carbon\Carbon::createFromTimestamp($last_activity);
							$dt = \Carbon\Carbon::now();

							if (($dt->timestamp - $last_activity) < 15)
								echo 'Online';
							else
								echo 'Offline - Last Activity: '.(($last_activity != -1) ? $carbonVer->diffForHumans() : 'Never');
						?>
					</p>
		        </div>
		    </div>
	    </a>
	</div>
	@if($key % 3 == 1) 
	</div> 
	@endif
@endforeach
@stop