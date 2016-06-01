
BoardGame.Game = function (game) {

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

};

BoardGame.Game.prototype = {
	init: function () {

	},

	preload: function () {
		// Add the Isometric plug-in to Phaser
	    game.plugins.add(new Phaser.Plugin.Isometric(game));

		// Set the world size
		game.world.setBounds(0, 0, 2048, 1024);

		// Start the physical system
		game.physics.startSystem(Phaser.Plugin.Isometric.ISOARCADE);

		// set the middle of the world in the middle of the screen
		game.iso.anchor.setTo(0.5, 0);
	},

	create: function () {
		floorGroup = game.add.group();
	    itemGroup = game.add.group();
	    grassGroup = game.add.group();
	    obstacleGroup = game.add.group();

	    var tile;
        for (var xt = 10; xt > 0; xt--) {
            for (var yt = 10; yt > 0; yt--) {
            	/*
            	var rnd = rndNum(20);
            	
            	if (rnd == 0) {
            		grassTile = game.add.isoSprite(xt, yt, 0, 'grass1', 0, grassGroup);
            		grassTile.anchor.set(0.5);
            	}
            	else if (rnd == 1)
            	{
            		grassTile = game.add.isoSprite(xt, yt, 0, 'grass2', 0, grassGroup);
            		grassTile.anchor.set(0.5);
            	}
            	else if (rnd == 2)
            	{
            		grassTile = game.add.isoSprite(xt, yt, 0, 'grass3', 0, grassGroup);
            		grassTile.anchor.set(0.5);
            	}*/
        		tile = game.add.isoSprite(xt * 100, yt * 100, 0, 'base_tile', 0, grassGroup);
        		tile.anchor.set(0.5);
            }
        }
		
		this.sprite = game.add.sprite(game.scale.width / 2,game.scale.height / 2,'hisui-idle');
		this.sprite.anchor.set(0.5);
		
		this.sprite.animations.add('idle', null, 20, true);
		this.sprite.animations.play('idle');

		this.sprite.currPalette = 0;
		this.sprite.paletteData = game.cache.getJSON('hisui-palettes');
		this.sprite.atlasData = game.cache.getJSON('hisui-atlas');

        //this.loadPalette(this.sprite, 'hisui-idle');
	    
		this.playLoop(1);
	},

	update: function () {
		if (game.sound.music_vol !== undefined) {
			if (this.bgm_start !== undefined)
				this.bgm_start.volume = game.sound.music_vol;
			if (this.bgm_loop !== undefined)
				this.bgm_loop.volume = game.sound.music_vol;
		}

		if (game.input.activePointer.isDown) {
			game.camera.x += game.input.activePointer.prevPosition.x - game.input.activePointer.position.x;		
			game.camera.y += game.input.activePointer.prevPosition.y - game.input.activePointer.position.y;	
		}
		else {	
			game.input.enabled = game.input.activePointer.withinGame;
		}
		if (game.input.activePointer.prevPosition === undefined || !game.input.activePointer.position.equals(game.input.activePointer.prevPosition)) {
			game.input.activePointer.prevPosition = game.input.activePointer.position.clone();
		}
	},
	
	render: function() {
		
	},

	quitGame: function (pointer) {
		this.state.start('MainMenu');
	},

	movePlayer: function () {
		
	},

	playLoop: function(soundID) {
		if (game.cache.getJSON('music-data').start[soundID] === true) {
			if (this.bgm_loop === undefined) {
				this.bgm_loop = game.add.audio('bgm'+soundID+'_loop');
			}
			if (this.bgm_start === undefined) {
				this.bgm_start = game.add.audio('bgm'+soundID+'_start');
			}
		
			game.sound.setDecodedCallback([this.bgm_start, this.bgm_loop], function() {
				this.bgm_start.onStop.add(function() {
					this.bgm_loop.loopFull();
				}, this)
				this.bgm_start.play(); 
			}, this);
			return this.bgm_start;
		}
		else {
			if (this.bgm_loop === undefined) {
				this.bgm_loop = game.add.audio('bgm'+soundID+'_loop');
			}
			this.bgm_loop.loopFull();
			return this.bgm_loop;
		}
	},
};
