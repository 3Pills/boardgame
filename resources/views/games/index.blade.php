@extends('app')

@section('title') Game Lobby @stop

@section('content')
<div class="panel panel-default" style="width:700px; margin:auto;">
	<div class="panel-heading">Create Lobby</div>
	<div class="panel-body">
		@if ($errors->any())
			<div class="alert alert-danger">
				<strong>Error!</strong> The following errors encountered in your input:<br>
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
				</ul>
			</div>
		@endif
		<form method="POST" action="{{ url('/game') }}" role="form" class="form-horizontal" accept-charset="UTF-8">
			{{ csrf_field() }}
			<div class="form-group" style="margin-top:12px;">
				<label for="name" class="control-label col-sm-3">Name:</label>
				<div class="col-sm-9"> <input type="text" name="name" class="form-control"/> </div>
			</div>
			<div class="form-group">
				<label for="character" class="control-label col-sm-3">Character:</label>
				<div class="col-sm-9"> 
					<select name="character" class="form-control">
						<option value="1">Hisui</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="map" class="control-label col-sm-3">Character:</label>
				<div class="col-sm-9"> 
					<select name="map" class="form-control">
						<option value="1">Downtown</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="private" class="control-label col-sm-3">Private:</label>
				<div class="col-sm-9 control-label" style="text-align:left;"> <input type="checkbox" name="private"/> </div>
			</div>
			<div class="form-group">
				<div class="col-sm-9 col-sm-offset-3">
                    <input type="submit" value="Create Lobby" class="btn btn-primary btn-block">            
				</div>
			</div>
		</form>
	</div>
</div>

<?php $mapNames = [1=>'Downtown']; ?>
<div>
	<h3>Public Lobbies</h3>
	@foreach (\App\Game::all()->sortBy('name') as $key=>$game)
		<div style="min-width:128px; padding:2px;">
			<a href="{{'game/'.$game->url}}" class="">
				<div class="panel panel-default panel-body">
					<img src="{{ url('./assets/images/default-avatar.png') }}" alt="avatar" class="profile-avatar" style="float:left;width:34px;height:34px;margin-right:8px;"/>
			    	<div>
			        	<p>Game Name: {{$game->name}}</p>
			        	<p>Game Map: {{ isset($mapNames[$game->map]) ? $mapNames[$game->map] : "InvalidMap" }} </p>
						@foreach ($game->players()->get() as $key=>$player)
				        	<p>{{ $player->id }}</p>
						@endforeach
			        </div>
			    </div>
		    </a>
		</div>
	@endforeach
</div>
@stop