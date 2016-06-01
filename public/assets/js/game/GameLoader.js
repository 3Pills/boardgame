
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
	init: function (spriteData) {
		console.log(spriteData);
		this.spriteData = spriteData;
	},

	preload: function () {
		this.preloadBar = this.add.sprite(game.world.centerX, game.world.centerY, 'preloadBar');
		this.preloadBar.position.x = game.world.centerX - this.preloadBar.width / 2;
		this.preloadBar.position.y = game.world.centerY - this.preloadBar.height;

		game.load.setPreloadSprite(this.preloadBar);

        game.load.image('base_tile', base_url + 'assets/sprites/stage/tile_large.png');
		
		this.sprite = game.add.sprite(game.scale.width / 2,game.scale.height / 2,'hisui-idle');
		this.sprite.anchor.set(0.5);
		
		this.sprite.animations.add('idle', null, 20, true);
		this.sprite.animations.play('idle');

		this.sprite.currPalette = 0;
		this.sprite.paletteData = game.cache.getJSON('hisui-palettes');
		this.sprite.atlasData = game.cache.getJSON('hisui-atlas');

        this.loadPalette(this.sprite, 'hisui-idle');
	},

	create: function () {
		$.post({
			url: window.location +'/playerLoaded',
			context: this,
			success: function(playerData) {
				if (playerData.length > 0) {
					
				}
	    	}
		});

	    this.timers = {
	    	pingPlayers: game.time.create(false).loop(500, this.pingPlayers, this).timer
	    } 
	    this.timers.pingPlayers.start();
	},

	update: function () {
		//	Honestly, just about anything could go here. It's YOUR game after all. Eat your heart out!
	},
	
	render: function() {
		
	},

	quitGame: function (pointer) {
		this.state.start('MainMenu');
	},

	pingPlayers: function () {
		$.get({
			url: window.location +'/playerLoaded',
			context: this,
			success: function(playerData) {
				if (playerData.length > 0 && !this.stateChanged) {
					this.stateChanged = true;
					this.state.start('Game');
				}
	    	}
		});
	},
	
	loadPalette: function(sprite, baseTexture, number) {
		// Select a number within our range of palettes, if argument defined.
		if (number !== undefined) { sprite.currPalette = game.math.clamp(number, 0, sprite.paletteData.length-1); }		

		// Create array to store the texture data of our palettes.
		if (!sprite.loadedPalettes) { sprite.loadedPalettes = []; }

		if (sprite.loadedPalettes[sprite.currPalette] === undefined) {
			//Create a new bitmapdata object to store the sprite palette. Each palette requires its own bitmapdata, as each represents a new texture.
			sprite.loadedPalettes[sprite.currPalette] = game.make.bitmapData();
			var bmd = sprite.loadedPalettes[sprite.currPalette];
			bmd.load(baseTexture); 

			//If we want the default palette, we shouldn't try to replace anything!
			if (sprite.currPalette != 0) {
				//Optimisation of rgb replacement. Optimises replaceRGB function call to only be run on different color values.
				if (!sprite.nonDupes) { 
					//Store a string of each different combined RGB value, so duplicates are not replaced. Minor optimisation.
					sprite.nonDupes = []; 
					for (var i = sprite.paletteData[0].length; i--;) {
						var joined = sprite.paletteData[0][i].join();
						if (sprite.nonDupes.indexOf(joined) <= -1) {
							sprite.nonDupes[i] = joined;
						}
					}
				}
				var defRGB = sprite.paletteData[0];
				var newRGB = sprite.paletteData[sprite.currPalette];
				// Loop through all palette data and replace each color one by one.
				for (var slot in sprite.nonDupes) {				
					bmd.replaceRGB(defRGB[slot][0],defRGB[slot][1],defRGB[slot][2],255,newRGB[slot][0],newRGB[slot][1],newRGB[slot][2],255);
				}
			}
			//Store bitmap data and animation data on the game cache, for instant re-access.
			game.cache.addTextureAtlas(baseTexture+sprite.currPalette, null, bmd.canvas, sprite.atlasData, Phaser.Loader.TEXTURE_ATLAS_JSON_HASH);
		}
		sprite.loadTexture(baseTexture+sprite.currPalette, this.sprite.animations.currentAnim.currentFrame.index, false);
	},
};
