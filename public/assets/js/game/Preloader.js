
BoardGame.Preloader = function (game) {
	this.background = null;
	this.preloadBar = null;

	this.ready = false;
};

BoardGame.Preloader.prototype = {

	preload: function () {
		this.background = this.add.sprite(0, 0, 'preloadBackground');

		this.preloadBar = this.add.sprite(game.world.centerX, game.world.centerY, 'preloadBar');
		this.preloadBar.position.x = game.world.centerX - this.preloadBar.width / 2;
		this.preloadBar.position.y = game.world.centerY - this.preloadBar.height;

		game.load.setPreloadSprite(this.preloadBar);

		game.load.atlasJSONHash('hisui-idle', base_url + 'assets/sprites/hisui/idle.png', base_url + 'assets/sprites/hisui/idle.json');
		game.load.atlasJSONHash('start-button', base_url + 'assets/sprites/ui/start_button.png', base_url + 'assets/sprites/ui/start_button.json');

		game.load.json('hisui-atlas', base_url + 'assets/sprites/hisui/idle.json');
		game.load.json('hisui-palettes', base_url + 'assets/sprites/hisui/hisui-palettes.json');

		var musicData = game.cache.getJSON('music-data');
		for (var key = musicData.start.length; key--;) {
			if (key < 2) {
				if (musicData.start[key] === true) {
					game.load.audio('bgm'+key+'_start', music_url+key+'_start.ogg');
					console.log('loaded \'' + music_url+key+'_start.ogg\' to cache \'bgm'+key+'_start\'');
				}
				game.load.audio('bgm'+key+'_loop', music_url+key+'_loop.ogg');
				console.log('loaded \'' + music_url+key+'_loop.ogg\' to cache \'bgm'+key+'_loop\'');
			}
		}
		//game.load.json('music-data', music_url +'/data.json');
		//this.load.audio('bgm1_start', base_url + 'assets/audio/music/melty/actors_anteroom_start.ogg');
		//this.load.audio('bgm1_loop', base_url + 'assets/audio/music/melty/actors_anteroom_loop.ogg');

		//this.load.audio('bgm2_start', base_url + 'assets/audio/music/take_off_start.ogg');
		//this.load.audio('bgm2_loop', base_url + 'assets/audio/music/take_off_loop.ogg');
	},

	create: function () {
		game.time.events.add(Phaser.Timer.SECOND * 2, function() {
			this.state.start('MainMenu');
		}, this);
	},

	//Send a request to the server to see if the game is still in selection phase.
	post_join: function() {
		$.get({
			url: window.location+'/join',
			data: {stage : 0},
			context: this,
			error: function(data, status, error) {
				switch (status) {
					case "timeout":
						return this.post_join();
						break;
					case "error":
						console.log("Error in GET to " + window.location +'/join :' + error);
						break;
					default:
						break;
				}
			},
			success: function(data) {
				if (data.slotAvailable == true) {
					this.state.start('MainMenu');
				}
				else {
					this.state.start('Game');
				}
	    	},
		});
	},

};
