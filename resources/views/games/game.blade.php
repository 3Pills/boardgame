@extends('app')

@section('title') Game @stop

@section('metadata')
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@stephenkoren7" />
<meta property="og:url" content="{{url('/game/').'/'.$game->url}}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{$game->name}} Lobby" />
<meta property="og:description" content="Game Lobby for {{$game->name}} on Play Vidya Soon" />
@stop

@section('css')
	<style> .navbar { margin: 0px; } </style>
@stop

@section('post-content')
	<div id="button" class="text-center" style="margin:19% 0px;">
		<button class="btn btn-large btn-default" onclick="startGame()">Let's fuckin gooooo</button>
	</div>
	<div class="container-game">
		<div id="gameContainer"></div>
		<!--<button class="btn btn-large btn-default" onclick="startGame()">Reload</button>-->
	</div>
@stop

@section('scripts-deferred')
    <script src="https://cdn.jsdelivr.net/phaser/2.4.7/phaser.js"></script>
    <script src="{{ url('/assets/js/Boot.js') }}"></script>

	<script type="text/javascript">
		var game;
		//var game_url = base_url + '{{ 'game/'.$game->url.'/' }}';

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
	
    <script src="{{ url('/assets/js/Preloader.js') }}"></script>
    <script src="{{ url('/assets/js/MainMenu.js') }}"></script>
    <script src="{{ url('/assets/js/Game.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js"></script>
@stop