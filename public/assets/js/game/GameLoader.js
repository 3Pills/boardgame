
BoardGame.GameLoader = function (game) {

	//	When a State is added to Phaser it automatically has the following properties set on it, even if they already exist:

    this.game;		//	a reference to the currently running game
    this.add;		//	used to add sprites, text, groups, etc
    this.camera;	//	a reference to the game camera
    this.cache;		//	the game cache
    this.input;		//	the global input manager (you can access this.input.keyboard, this.input.mouse, as well from it)
    this.load;		//	for preloading assets
    this.math;		//	lots of useful common math operations
    this.sound;		//	the sound manager - add a sound, play one, set-up markers, etc
    this.stage;		//	the game stage
    this.time;		//	the clock
    this.tweens;    //  the tween manager
    this.state;	    //	the state manager
    this.world;		//	the game world
    this.particles;	//	the particle manager
    this.physics;	//	the physics manager
    this.rnd;		//	the repeatable random number generator

    //	You can use any of these from any function within this State.
    //	But do consider them as being 'reserved words', i.e. don't create a property for your own game called "world" or you'll over-write the world reference.

    this.stateChanged = false;
};

BoardGame.GameLoader.prototype = {
	init: function (bgm_start, bgm_loop) {
		this.bgm_start = bgm_start;
		this.bgm_loop = bgm_loop;
	    this.latest = {
	    	'pData': 0,
	    };

		this.preloadBar = game.add.sprite(game.world.centerX, game.world.centerY, 'preloadBar');
		this.preloadBar.position.x = game.world.centerX - this.preloadBar.width / 2;
		this.preloadBar.position.y = game.world.centerY - this.preloadBar.height;

		game.load.setPreloadSprite(this.preloadBar);

		this.loadingText = game.add.text(512, 550, "Loading...", { font: "14px Arial", fill: "#ffffff", align: "center" });
	},

	preload: function () {

		this.loadingText.text = "Loading Character Data...";
		var count = 0;
		var palettes = {};
		for (var i = game.players.length - 1; i >= 0; i--) {
			game.players[i].sprite = game.add.sprite(320 + 128 * count, 400, game.players[i].character + '_idle');
			game.players[i].sprite.anchor.set(0.5, 1);
			
			game.players[i].sprite.animations.add(game.players[i].character + '_idle', null, 20, true);
			game.players[i].sprite.animations.play(game.players[i].character + '_idle');

			game.players[i].sprite.currCharacter = game.players[i].character;
			game.players[i].sprite.currPalette = game.players[i].palette;

			if (!palettes[game.players[i].character]) {
				palettes[game.players[i].character] = {};
			}
			palettes[game.players[i].character][game.players[i].palette] = true;

			game.players[i].sprite.paletteData = game.cache.getJSON(game.players[i].character + '_palettes');
        	game.players[i].sprite.loadPalette(game.players[i].character, '_idle');
        	count += 1;
		}

		this.loadingText.text = "Loading UI Elements...";
		game.load.atlasJSONHash('roll_button', base_url + 'assets/sprites/ui/roll_button.png', base_url + 'assets/sprites/ui/roll_button.json');

		this.loadingText.text = "Loading Map Elements...";
		this.loadingText.anchor.set(0.5, 1);

        game.load.image('base_tile', base_url + 'assets/sprites/stage/tile_large.png');
        game.load.image('point', base_url + 'assets/sprites/point.png');
        
		game.load.json('map_data', base_url + 'assets/json/board1.json');

		this.loadingText.text = "Loading Character Data...";
		var characterData = game.cache.getJSON('character_data');
		for (var key = characterData.length; key--;) {
			var dir = base_url + 'assets/sprites/' + characterData[key].dir + '/';

			key

			//Load all assets and apply palette swaps to them here.

			//game.load.atlasJSONHash(key + '_idle', dir + 'idle.png', dir + 'idle.json');
			//game.load.json(key + '_atlas', dir + 'idle.json');
			//game.load.json(key + '_palettes', dir + 'palettes.json');
		}

		this.loadingText.text = "Connecting to game...";
	},

	create: function () {
		this.postLoaded();
	    this.timers = {
	    	pingPlayerStates: game.time.create(false).loop(1500, this.pingPlayerStates, this, 'state').timer
	    } 
	    this.timers.pingPlayerStates.start();
	},

	update: function () {
		//	Honestly, just about anything could go here. It's YOUR game after all. Eat your heart out!
	},
	
	render: function() {
		
	},

	quitGame: function (pointer) {
		this.state.start('MainMenu');
	},

	postLoaded: function() {
		$.post({
			url: window.location +'/loaded',
			context: this,
			success: function(playerData) {
				// Do stuff when you've been approved as finished loading.
				this.loadingText.text = "Waiting for other players...";
	    	}
		});
	},

	pingPlayerStates: function(state) {
		this.getPlayerData(state);
	},

	getPlayerData: function(key) {
		$.get({
			url: window.location+'/pData',
			context: this,
			data: {ts: this.latest.pData, key: key },
			success: function(data) {
				if (Object.keys(data).length > 0) {
					this.latest.pData = data.time;

					for (var userID in data.players) {
						for (var i = game.players.length - 1; i >= 0; i--) {
							if (game.players[i].user_data.id == userID) {
								game.players[i][key] = data.players[userID];
							}
						}
					}
					this.successfulUpdate(key);
				}
			}
		});
	},

	successfulUpdate: function(key) {
		switch(key) {
			case 'state':
				var notReady = false;
				this.loadingText.text = "Waiting for other players... (";
				for (var i = game.players.length - 1; i >= 0; i--) {
					if (game.players[i].state != 2 || game.players[i].state == 101) {
						notReady = true;
						this.loadingText.text += game.players[i].user_data.name + ", ";
					}
				}
				this.loadingText.text += ")";

				if (notReady) return;
				if (this.bgm_start !== undefined)
					this.bgm_start.stop();
				if (this.bgm_loop !== undefined)
					this.bgm_loop.stop();
				this.state.start('Game');
				break;
			default:
				break;
		}
	}
};
