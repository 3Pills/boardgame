
BasicGame.Preloader = function (game) {

	this.background = null;
	this.preloadBar = null;

	this.ready = false;

};

BasicGame.Preloader.prototype = {

	preload: function () {

		//	These are the assets we loaded in Boot.js
		//	A nice sparkly background and a loading progress bar

		this.background = this.add.sprite(0, 0, 'preloadBackground');

		this.preloadBar = this.add.sprite(game.world.centerX, game.world.centerY, 'preloadBar');
		this.preloadBar.position.x = game.world.centerX - this.preloadBar.width / 2;
		this.preloadBar.position.y = game.world.centerY - this.preloadBar.height;

		//	This sets the preloadBar sprite as a loader sprite.
		//	What that does is automatically crop the sprite from 0 to full-width
		//	as the files below are loaded in.

		this.load.setPreloadSprite(this.preloadBar);

		//	Here we load the rest of the assets our game needs.
		//	You can find all of these assets in the Phaser Examples repository

	    // this.load.image('tetris1', 'assets/sprites/tetrisblock1.png');
	    // this.load.image('tetris2', 'assets/sprites/tetrisblock2.png');
	    // this.load.image('tetris3', 'assets/sprites/tetrisblock3.png');
	    // this.load.image('hotdog', 'assets/sprites/hotdog.png');
	    // this.load.image('starfield', 'assets/skies/deep-space.jpg');

		this.load.atlasJSONHash('hisui-idle', base_url + 'assets/sprites/hisui/idle.png', base_url + 'assets/sprites/hisui/idle.json');

		this.load.json('hisui-atlas', base_url + 'assets/sprites/hisui/idle.json');
		this.load.json('hisui-palettes', base_url + 'assets/sprites/hisui/hisui-palettes.json');
		
		//this.load.audio('music', 'assets/audio/123.mp3');
        this.load.image('slider_tab', base_url + 'assets/sprites/slider_tab.png');
		
		
		//this.load.audio('bgm1_start', base_url + 'assets/audio/music/melty/actors_anteroom_start.ogg');
		//this.load.audio('bgm1_loop', base_url + 'assets/audio/music/melty/actors_anteroom_loop.ogg');

		//this.load.audio('bgm2_start', base_url + 'assets/audio/music/take_off_start.ogg');
		//this.load.audio('bgm2_loop', base_url + 'assets/audio/music/take_off_loop.ogg');

		this.load.audio('bgm3_loop', base_url + 'assets/audio/music/key/wake_in_the_mornin.ogg');
	},

	create: function () {
		game.time.events.add(Phaser.Timer.SECOND * 2, function() {
			this.state.start('MainMenu');
		}, this);
	}

};
