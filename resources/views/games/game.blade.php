@extends('app')

@section('title') Game @stop

@section('includes')
    {!! HTML::script('https://cdn.jsdelivr.net/phaser/2.4.7/phaser.js') !!}
    {!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js') !!}
    {!! HTML::script('/js/Boot.js') !!}
    {!! HTML::script('/js/Preloader.js') !!}
    {!! HTML::script('/js/MainMenu.js') !!}
    {!! HTML::script('/js/Game.js') !!}

	<style>
	.navbar {
		margin: 0px;
	}
	</style>

	<script type="text/javascript">
		var game;
		//var game_url = base_url + '{{ 'game/'.$url.'/' }}';

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