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
	<div class="container-game">

		<div class="game-overlay" >
			<button data-toggle="collapse" data-target="#options" class="options-button">Options <span class="caret"></span></button>
			<div id="options" class="options collapse">
				<div class="options-window"></div>
				<div class="chat-text">
					Sound: <input type="range" name="sound-range" id="sound-range" class="chat-input" min="0" max="100" oninput="changeVolSound(this.value)" onchange="changeVolSound(this.value)">
					Music: <input type="range" name="music-range" id="music-range" class="chat-input" min="0" max="100" oninput="changeVolMusic(this.value)" onchange="changeVolMusic(this.value)">
				</div>
				<button data-toggle="collapse" data-target="#options" style="width:100%;z-index:2;position:relative;">Chat <span class="caret"></span></button>
			</div>

			<button data-toggle="collapse" data-target="#chat" class="chat-button">Chat <span class="caret"></span></button>
			<div id="chat" class="chat collapse">
				<div class="chat-window"></div>
				<div id="chat-text" class="chat-text">
					<div class="text-console">Welcome to the Chat!</div>
				</div>
				<div id="chat-text" class="chat-text">
					<div style="width:7%; display:inline-block;">Chat:</div><input type="text" name="chat-input" id="chat-input" class="chat-input" style="width:93%"/>
				</div>
				<button data-toggle="collapse" data-target="#chat" style="width:100%;z-index:2;position:relative;">Chat <span class="caret"></span></button>
			</div>
			
			<div id="gameContainer"></div>

		</div>
		<!--<button class="btn btn-large btn-default" onclick="startGame()">Reload</button>-->
	</div>
@stop

@section('scripts-deferred')
    <script src="https://cdn.jsdelivr.net/phaser/2.4.7/phaser.js"></script>
    <script src="{{ url('/assets/js/game/Boot.js') }}"></script>	
    <script src="{{ url('/assets/js/game/Preloader.js') }}"></script>
    <script src="{{ url('/assets/js/game/MainMenu.js') }}"></script>
    <script src="{{ url('/assets/js/game/GameLoader.js') }}"></script>
    <script src="{{ url('/assets/js/game/Game.js') }}"></script>
    <script src="{{ url('/assets/js/game/GameFinish.js') }}"></script>
    <script src="{{ url('/assets/js/phaser-plugin-isometric.js') }}"></script>

	<script type="text/javascript">
		//Canvas is used cause FireFox doesn't handle WebGL Sprites very well...
		var game = new Phaser.Game(1024, 576, Phaser.AUTO, 'gameContainer', BoardGame.Boot, false, false);
		game.state.add('Boot', BoardGame.Boot);
		game.state.start('Boot');

        var music_url = base_url + 'assets/audio/music/' + 'lb/';

		function changeVolSound(value) { game.sound.sound_vol = value / 100; }
		function changeVolMusic(value) { game.sound.music_vol = value / 100; }

		window.onbeforeunload = function () {
			//return "You are about to navigate away from the game window."
		}
		window.onunload = function() {

		}
	</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js"></script>
    <script>
    	var latest_chat = moment.utc(0).format();

    	$(document).ready(function() {
			$('.chat-input').keyup(function(e){
			    if(e.keyCode === 13) {
			    	postChat();
			        return false;
			    }
			});

	        $("#music-range").val(10);
	        $("#sound-range").val(50);
		});

		function pullData() {
		    retrieveChatMessages();
		    setTimeout(pullData,7000);
		}

		function loadChatData(msgData) {
			for (var k in msgData) {
				if (!isNaN(k)) {
					latest_chat = moment.utc(msgData[k].time).format();
					if (msgData.user_data !== undefined) {
            			$('#chat-text').append('<div id='+'1'+' class=chat-player-'+'1'+'> ['+moment.utc(msgData[k].created_at).toDate().toLocaleTimeString() + '] '+ msgData.user_data[msgData[k].user_id].name + ': '+msgData[k].msg+'</div>');
					}
				}
			}
    		//$('.collapse').collapse("show");
    		//setTimeout(function() {$('.collapse').collapse("hide")}, 4000);
		}

		function retrieveChatMessages() {
			$.get({
				url: window.location+'/chat',
				data: {latest_chat : latest_chat},
				success: loadChatData,
			});
		}

		function postChat() {
			var chatString = document.getElementById('chat-input').value;
			document.getElementById('chat-input').value = '';
			if (chatString.length > 0) {
				var msg = chatString;
				chatString = "";
				$.post({
					url: window.location+'/chat',
					data: {msg: msg, latest_chat : latest_chat},
					context: this,
					success: loadChatData,
				});
			}
		}

		pullData();
    </script>
@stop