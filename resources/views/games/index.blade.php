@extends('app')

@section('title') Game Lobby @stop

@section('includes')
    {!! HTML::script('https://cdn.jsdelivr.net/phaser/2.4.7/phaser.js') !!}
    {!! HTML::script('/js/Boot.js') !!}
    {!! HTML::script('/js/Preloader.js') !!}
    {!! HTML::script('/js/MainMenu.js') !!}
    {!! HTML::script('/js/Game.js') !!}

	<!--
	<style>
	.navbar {
		margin: 0px;
	}
	</style>
	<script type="text/javascript">
		var game;

		function startGame() {
			if (document.getElementById("button") != null) {
				document.getElementById("button").remove();
			}

			//Canvas is used cause FireFox doesn't handle WebGL Sprites very well...
			game = new Phaser.Game(1024, 576, Phaser.CANVAS, 'gameContainer', BasicGame.Boot, false, false);
			game.state.add('Boot', BasicGame.Boot);
			game.state.start('Boot');
		}
	</script>
	-->
@stop

@section('content')
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default ">
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
				{!! Form::open(['role' => 'form', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						{!! Form::label('name', 'Name:', ['class' => 'control-label col-sm-3']) !!}
			    		<div class="col-sm-9">
							{!! Form::text('name', null, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('character', 'Character:', ['class' => 'control-label col-sm-3']) !!}
			    		<div class="col-sm-9">
							{!! Form::select('map', array(1 => 'Hisui'), 1, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('map', 'Map:', ['class' => 'control-label col-sm-3']) !!}
			    		<div class="col-sm-9">
							{!! Form::select('map', array(1 => 'Downtown'), 1, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('private', 'Private:', ['class' => 'control-label col-sm-3']) !!}
			    		<div class="col-sm-9 control-label" style="text-align:left;">
							{!! Form::checkbox('private') !!}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							{!! Form::submit('Create Lobby', ['class' => 'btn btn-primary form-control']) !!}
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@stop