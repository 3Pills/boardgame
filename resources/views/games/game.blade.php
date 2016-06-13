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

@section('content')
	<div class="container-game docked">
		<div class="game-overlay-top">
			<div class="game-overlay-right">
				<button data-toggle="collapse" data-target="#options" class="game-overlay-options-button"><span class="glyphicon glyphicon-wrench"></span></button>
				<div id="options" class="game-overlay-options collapse">
					<div class="game-overlay-options-menu">
						Sound: <input type="range" name="sound-range" id="sound-range" class="chat-input" min="0" max="100" oninput="changeVolSound(this.value)" onchange="changeVolSound(this.value)">
						Music: <input type="range" name="music-range" id="music-range" class="chat-input" min="0" max="100" oninput="changeVolMusic(this.value)" onchange="changeVolMusic(this.value)">
					</div>
				</div>
			</div>

			<!--
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
			-->
		</div>

		<div class="game-overlay-bottom">
			<div class="game-overlay-right">
				<button class="game-overlay-fullscreen-button"><span class="glyphicon glyphicon-resize-full"></span></button>
			</div>
		</div>
		
		<div id="gameElement" class="game-element"></div>
		<!--<button class="btn btn-large btn-default" onclick="startGame()">Reload</button>-->
	</div>
@stop

@section('scripts-deferred')
	<!-- Polyfills -->
	<script type="text/javascript">
		Number.isInteger = Number.isInteger || function(value) {
			return typeof value === "number" && isFinite(value) && Math.floor(value) === value;
		};
	</script>

	<!-- Phaser -->
    <script src="https://cdn.jsdelivr.net/phaser/2.4.7/phaser.js"></script>
    <script src="{{ url('/assets/js/game/Boot.js') }}"></script>	
    <script src="{{ url('/assets/js/game/Preloader.js') }}"></script>
    <script src="{{ url('/assets/js/game/MainMenu.js') }}"></script>
    <script src="{{ url('/assets/js/game/GameLoader.js') }}"></script>
    <script src="{{ url('/assets/js/game/Game.js') }}"></script>
    <script src="{{ url('/assets/js/game/GameFinish.js') }}"></script>
    <script src="{{ url('/assets/js/phaser-plugin-isometric.js') }}"></script>

	<script type="text/javascript">
		function changeVolSound(value) { if (game.sound.sound !== undefined) game.sound.sound.volume = value / 100; }
		function changeVolMusic(value) { if (game.sound.music !== undefined) game.sound.music.volume = value / 100; }

		Phaser.SoundManager.prototype.playMusic = function(soundID) {
			if (this.music === undefined) this.music = {};
			if (game.cache.getJSON('music_data').start[soundID] === true) {
				if (game.cache.checkSoundKey('bgm'+soundID+'_loop') === false || game.cache.checkSoundKey('bgm'+soundID+'_start') === false) { return; }
				if (this.music.loop === undefined) this.music.loop  = this.add('bgm'+soundID+'_loop');
				else {
					this.music.loop.destroy();
					this.music.loop = this.add('bgm'+soundID+'_loop');
				}
				if (this.music.start === undefined) this.music.start = this.add('bgm'+soundID+'_start');
				else {
					this.music.start.destroy();
					this.music.start = this.add('bgm'+soundID+'_start');
				}
				if (this.music.loop  === undefined || this.music.start === undefined) return;
				
				this.music.start.volume = this.music.volume;
				this.music.loop.volume = this.music.volume;

				this.setDecodedCallback([this.music.start, this.music.loop], function() {
					this.music.start.onStop.add(function() { this.music.loop.loopFull(); }, this)
					this.music.start.play(); 
				}, this);

				return this.music.start;
			}
			else {
				if (game.cache.checkSoundKey('bgm'+soundID+'_loop') === false) { return; }
				if (this.music.loop === undefined) this.music.loop = this.add('bgm'+soundID+'_loop');
				else {
					this.music.loop.destroy();
					this.music.loop = this.add('bgm'+soundID+'_loop');
				}
				if (this.music.loop === undefined) return;
				this.music.loop.volume = this.music.volume;
				
				this.setDecodedCallback([this.music.loop], function() { this.music.loop.loopFull(); }, this);
				return this.music.loop;
			}
		}

		Phaser.Sprite.prototype.loadPalette = function(characterID, animKey, reloadAnim) {
			if (characterID === undefined || animKey === undefined) return;
			// Select a number within our range of palettes, if argument defined.
			// if (Number.isInteger(number)) { this.currPalette = game.math.clamp(number, 0, this.paletteData.length-1); }		

			// Create array to store the texture data of our palettes.
			if (game.loadedPalettes === undefined) { 
				game.loadedPalettes = [];
				game.loadedPalettes[characterID] = [];
				game.loadedPalettes[characterID][this.currPalette] = [];
			}
			if (game.loadedPalettes[characterID] === undefined) { 
				game.loadedPalettes[characterID] = []; 
				game.loadedPalettes[characterID][this.currPalette] = [];
			}
			if (game.loadedPalettes[characterID][this.currPalette] === undefined) { 
				game.loadedPalettes[characterID][this.currPalette] = []; 
			}

			if (game.loadedPalettes[characterID][this.currPalette][animKey] === undefined) {
				//Create a new bitmapdata object to store the sprite palette. Each palette requires its own bitmapdata, as each represents a new texture.
				game.loadedPalettes[characterID][this.currPalette][animKey] = game.make.bitmapData();
				var bmd = game.loadedPalettes[characterID][this.currPalette][animKey];
				bmd.load(characterID + animKey); 

				//If we want the default palette, we shouldn't try to replace anything!
				if (this.currPalette !== 0) {
					//Optimisation of rgb replacement. Optimises replaceRGB function call to only be run on different color values.
					if (!this.nonDupes) { 
						//Store a string of each different combined RGB value, so duplicates are not replaced. Minor optimisation.
						this.nonDupes = []; 
						for (var i = this.paletteData[0].length; i--;) {
							var joined = this.paletteData[0][i].join();
							if (this.nonDupes.indexOf(joined) <= -1) {
								this.nonDupes[i] = joined;
							}
						}
					}
					var defRGB = this.paletteData[0];
					var newRGB = this.paletteData[this.currPalette];
					// Loop through all palette data and replace each color one by one.
					for (var slot in this.nonDupes) {				
						bmd.replaceRGB(defRGB[slot][0],defRGB[slot][1],defRGB[slot][2],255,newRGB[slot][0],newRGB[slot][1],newRGB[slot][2],255);
					}
				}
				//Store bitmap data and animation data on the game cache, for instant re-access.
				if (reloadAnim !== false) {
					game.cache.addTextureAtlas(characterID + animKey + ((this.currPalette == 0) ? '' : this.currPalette), null, bmd.canvas, game.cache.getJSON(this.currCharacter + animKey + '_atlas'), Phaser.Loader.TEXTURE_ATLAS_JSON_HASH);
				}
				else {
					game.cache.addImage(characterID + animKey + ((this.currPalette == 0) ? '' : this.currPalette), null, bmd.canvas);
				}
			}
			this.loadTexture(characterID + animKey + ((this.currPalette == 0) ? '' : this.currPalette), (reloadAnim !== false) ? this.animations.currentAnim.currentFrame.index : null, false);
			if (reloadAnim === true) {
				this.animations.add(characterID + animKey, null, game.cache.getJSON(this.currCharacter + animKey + '_atlas').meta.fps, true);
				this.animations.play(characterID + animKey);
			}
		}

		window.onbeforeunload = function () {
			//return "You are about to navigate away from the game window."
		}
		window.onunload = function() {

		}

		$('.game-overlay-fullscreen-button').click(function() {
			if (game.scale.isFullScreen)
				game.scale.stopFullScreen();
			else 
				game.scale.startFullScreen();
		});

		//Canvas is used cause FireFox doesn't handle WebGL Sprites very well...
		var game = new Phaser.Game(1024, 576, Phaser.CANVAS, 'gameElement', BoardGame.Boot, false, false);

        var user_id = <?php echo \Auth::user()->id; ?>

		game.state.add('Boot', BoardGame.Boot);
		game.state.start('Boot');
	</script>

	<!-- Non-Important Includes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js"></script>

	<!-- Native JavaScript -->
    <script>
    	var latest_chat = moment.utc(0).format();

    	$(document).ready(function() {
			$('.chat-input').keyup(function(e){
			    if(e.keyCode === 13) {
			    	postChat();
			        return false;
			    }
			});

	        $("#music-range").val(00);
	        $("#sound-range").val(50);
		});

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

		/*
		function pullData() {
		    retrieveChatMessages();
		    setTimeout(pullData,7000);
		}

		pullData();
		*/
    </script>
@stop