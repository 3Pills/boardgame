@extends('app')

@section('title') Game Lobby @stop

@section('content')

<?php $mapNames = [0=>'Downtown']; ?>
<div class="row">
	<div class="col-sm-4">
		<h3>Lobbies</h3>
		<div class="lobby-list" style="height:500px;">
		@foreach (\App\Game::all()->sortBy('name') as $key=>$game)
			<div class="lobby-card well">
				<a href="{{'game/'.$game->url}}">				<div class="lobby-card-base" style="height:50px;">
					<div class="col-sm-2">
						<img src="{{ url('./assets/images/default-avatar.png') }}" alt="avatar" class="lobby-card-preview">
					</div>
		        	<div class="col-sm-10">
			        	<div>Game Name: {{ $game->name }}</div>
			        	<div>Game Map: {{ isset($mapNames[$game->map]) ? $mapNames[$game->map] : "InvalidMap" }} </div>
			        </div>
		        </div>
			    </a>

				<button data-toggle="collapse" data-target="#players" class="btn btn-default lobby-card-button">Players: {{$game->players()->count()}} <span class="caret"></span></button>
				<div id="players" class="collapse well well-sm row lobby-card-row">
					@foreach ($game->players()->get() as $player)
					<div class="col-xs-3 lobby-card-player">
						<div><img class="lobby-card-player-image" src="{{url('./assets/sprites/portrait/'.$player->character.'.png')}}"/></div>
						<div class="lobby-card-player-name">{{ \App\User::where('id', '=', $player->user_id)->first()->name }}</div>
					</div>
					@endforeach
				</div>
			</div>
		@endforeach
		</div>
		<button class="btn btn-primary btn-block">Create Lobby</button>
		<button class="btn btn-primary btn-block">Join Private Lobby</button>
		<button class="btn btn-primary btn-block">Refresh List</button>
	</div>

	<div class="col-sm-8">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#splash_screen">Welcome!</a></li>
			<li><a data-toggle="tab" href="#create_lobby">Create Lobby</a></li>
			<li><a data-toggle="tab" href="#join_lobby">Join Private Lobby</a></li>
		</ul>

		<section class="tab-content">
			<div id="splash_screen" class="tab-pane in active panel panel-default panel-tab panel-body">
				<p>Welcome! You've managed to make it to the game creation page. Make a lobby by clicking the tab above this window, or join an already existing game to get started!</p>
			</div>
			<div id="create_lobby" class="tab-pane panel panel-default panel-tab panel-body">
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
				<form id="lobby_creator" method="POST" action="{{ url('/game') }}" role="form" class="form-horizontal" accept-charset="UTF-8">
					{{ csrf_field() }}
					<div class="form-group" style="margin-top:12px;">
						<label for="name" class="control-label col-sm-3">Name:</label>
						<div class="col-sm-9"> <input type="text" name="name" class="form-control"/> </div>
					</div>
					<div class="form-group">
						<label for="map" class="control-label col-sm-3">Map:</label>
						<div class="col-sm-9"> 
							<select name="map" class="form-control">
								<option value="0">Downtown</option>
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
			<div id="join_lobby" class="tab-pane panel panel-default panel-tab panel-body">
				<form id="game_joiner" method="POST" action="{{ url('/join') }}" role="form" class="form-horizontal" accept-charset="UTF-8">
				</form>
			</div>
			<div id="menu2" class="tab-pane">

			</div>
			<div id="menu3" class="tab-pane">

			</div>
		</section>
	</div>
</div>

@stop

@section('scripts-deferred')
<!--
<script>
	$( "#lobby_creator" ).submit(function( event ) {
		event.preventDefault();
		var $form = $(this), url = $form.attr('action');
		var post = $.post({
			url: url,
			context: this,
			data: $form.serialize(),
			success: function(data) {
				alert('lobby created at '+data.url);
			}
		});
	});

	$( "#game_joiner" ).submit(function( event ) {
		event.preventDefault();
		var $form = $(this), url = $form.attr('action');
		var post = $.post({
			url: url,
			context: this,
			data: $form.serialize(),
			success: function(data) {
				alert('lobby created at '+data.url);
			}
		});
	});
</script>
-->
@stop