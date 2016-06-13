
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
	init: function () {
		this.preloadBar = game.add.sprite(game.world.centerX, game.world.centerY, 'preloadBar');
		this.preloadBar.position.x = game.world.centerX - this.preloadBar.width / 2;
		this.preloadBar.position.y = game.world.centerY - this.preloadBar.height;

		game.load.setPreloadSprite(this.preloadBar);

		this.loadingText = game.add.text(512, 550, "Loading...", { font: "14px Arial", fill: "#ffffff", align: "center" });
	},

	preload: function () {

		this.loadingText.text = "Loading Character Data...";
		var palettes = {};
		for (var i = game.players.length - 1; i >= 0; i--) {
			var ply = game.players[i];
			ply.sprite = game.add.sprite(320 + 128 * i, 400, ply.character + '_idle');
			ply.sprite.anchor.set(0.5, 1);
			
			ply.sprite.animations.add(ply.character + '_idle', null, game.cache.getJSON(ply.character + '_idle_atlas').meta.fps, true);
			ply.sprite.animations.play(ply.character + '_idle');

			ply.sprite.currCharacter = ply.character;
			ply.sprite.currPalette = ply.palette;

			if (!palettes[ply.character]) {
				palettes[ply.character] = {};
			}
			palettes[ply.character][game.players[i].palette] = true;

			ply.sprite.paletteData = game.cache.getJSON(game.players[i].character + '_palettes');
        	ply.sprite.loadPalette(game.players[i].character, '_idle');

        	game.load.image(ply.character + '_portrait', '../assets/sprites/portrait/'+ ply.character +'.png');
		}

		this.loadingText.text = "Loading UI Elements...";
		game.load.atlasJSONHash('roll_button', '../assets/sprites/ui/roll_button.png', '../assets/sprites/ui/roll_button.json');
        
        game.load.image('card_portrait', '../assets/sprites/portrait/card.png');

		this.loadingText.text = "Loading Map Elements...";
		this.loadingText.anchor.set(0.5, 1);

        game.load.image('base_tile', '../assets/sprites/stage/tile_large_3d.png');
        game.load.image('point', '../assets/sprites/point.png');
        
		game.load.json('map_data', '../assets/json/board1.json');

		this.loadingText.text = "Loading Character Data...";
		var characterData = game.cache.getJSON('character_data');
		for (var key = characterData.length; key--;) {
			var dir = '../assets/sprites/' + characterData[key].dir + '/';

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
	    	getPlayerData: game.time.create(false).loop(1500, this.getPlayerData, this, 'state').timer
	    } 
	    this.timers.getPlayerData.start();
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

	getPlayerData: function(key) {
		var keyData = {};
		for (var pID in game.players) {
			keyData[game.players[pID].user_data.id] = game.players[pID][key];
		}
		$.get({
			url: window.location+'/pData',
			context: this,
			data: {key: key, keyData: keyData },
			success: function(data) {
				if (Object.keys(data).length > 0) {
					for (var uID in data.players) {
						for (var i = game.players.length - 1; i >= 0; i--) {
							if (game.players[i].user_data.id == uID) {
								game.players[i][key] = data.players[uID];
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
					if (game.players[i].state < 2 && game.players[i].state != 101) {
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
