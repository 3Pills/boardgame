
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
		game.input.activePointer.prevPosition = game.input.activePointer.position.clone();
		this.turnInProgress = false;
		this.currPlayer = 0;
	},

	preload: function () {
		// Add the Isometric plug-in to Phaser
	    game.plugins.add(new Phaser.Plugin.Isometric(game));

		// Set the world size
		game.world.setBounds(0, 0, 4192, 2048);

		// Start the physical system
		game.physics.startSystem(Phaser.Plugin.Isometric.ISOARCADE);

		// set the middle of the world in the middle of the screen
		game.iso.anchor.setTo(0.5, 0.05);

		game.camera.setPosition(2048 - game.camera.width / 2, 1024 - game.camera.height / 2);
	},

	create: function () {
		floorGroup = game.add.group();
	    itemGroup = game.add.group();
	    grassGroup = game.add.group();
	    obstacleGroup = game.add.group();

	    this.mapData = game.cache.getJSON('map_data');

	    var tile;

	    var tileColors = {
	    	'0':{'0':'0xffccaa'},
	    	'1':{
		    	'0':'0x6666ff',
		    	'1':'0xff6666',
		    	'2':'0x66ff66',
		    	'3':'0xffff66' 
		    }
	    };

	    var spawnCoords = [];
		for (var coord in this.mapData.spaces) {
			var xy = coord.split('-');
			var tile = this.mapData.spaces[coord];
    		tile.sprite = game.add.isoSprite(xy[0] * 100, xy[1] * 100, 0, 'base_tile', 0, grassGroup);
    		tile.sprite.anchor.set(0.5);

    		if (tile.subType === undefined) tile.subType = 0;

    		if (tile.type === 1) spawnCoords[tile.subType] = coord;

    		if (tileColors[tile.type][tile.subType] !== undefined) tile.sprite.tint = tileColors[tile.type][tile.subType];
		}

        game.camera.pointTarget = game.add.sprite(0,0,'point');
        game.camera.pointTarget.anchor.set(0.5);
		game.camera.follow(game.camera.pointTarget, game.camera.FOLLOW_TOPDOWN_TIGHT, 0.3, 0.2)

		for (var i = game.players.length - 1; i >= 0; i--) {
			var xy = spawnCoords[i].split('-');
			game.players[i].sprite = game.add.isoSprite(100 * xy[0],100 * xy[1], 0, game.players[i].character + '_idle', 1);
			game.players[i].sprite.anchor.set(0.5, 1);
			
			game.players[i].sprite.animations.add(game.players[i].character + '_idle', null, 20, true);
			game.players[i].sprite.animations.play(game.players[i].character + '_idle');

			game.players[i].sprite.currCharacter = game.players[i].character;
			game.players[i].sprite.currPalette = game.players[i].palette;

			game.players[i].sprite.paletteData = game.cache.getJSON(game.players[i].character + '_palettes');
        	game.players[i].sprite.loadPalette(game.players[i].character, '_idle');

        	game.players[i].turnOrder = i;
        	game.players[i].currSpace = spawnCoords[i];

        	//Play the character's theme song, represented by their index number in the json file, starting at 1.
        	if (i == 1) {
        		//game.camera.playerTarget = count;
        		game.camera.pointTarget.position = game.players[i].sprite.position.clone();
				game.sound.playMusic(i+1);
        	}
		}
		this.endMove(0);

		var button = game.add.button(386, 476, 'roll_button', this.postRoll, this, 2, 1, 0);
		button.anchor.set(0.5);
		button.fixedToCamera = true;
        //this.loadPalette(this.sprite, 'hisui-idle');
	    
	},

	update: function () {
		if (game.input.activePointer.isDown && this.turnInProgress === false) {
			game.camera.pointTarget.x += game.input.activePointer.prevPosition.x - game.input.activePointer.position.x;		
			game.camera.pointTarget.y += game.input.activePointer.prevPosition.y - game.input.activePointer.position.y;	
		}
		else {
			game.input.enabled = game.input.activePointer.withinGame;
		}

		//this.updatePlayers();

		game.input.activePointer.prevPosition = game.input.activePointer.position.clone();
	},

	updatePlayers: function() {
		for (var i = game.players.length - 1; i >= 0; i--) {
			var currXY = game.players[i].currSpace.split('-');
			var nextXY = this.mapData.spaces[game.players[i].currSpace].next.split('-');
			game.players[i].sprite.scale.x = 1 * (nextXY[0] > currXY[0] || nextXY[1] < currXY[1]) ? 1 : -1;
		}
	},
	
	startMove: function(pID, amount) {
		this.turnInProgress = true;
		this.cameraFollowTight(game.players[pID].sprite);
		var baseTween;
		var prevTween;
		for (var i = amount-1; i >= 0; i--) {
			var nextXY = this.mapData.spaces[game.players[pID].currSpace].next.split('-');
			var tween = this.gridTween( pID, Number(nextXY[0]), Number(nextXY[1]), (i == 0) );
			game.players[pID].currSpace = this.mapData.spaces[game.players[pID].currSpace].next;

			if (prevTween !== undefined) prevTween.chain(tween);
			if (baseTween === undefined) baseTween = tween;

			prevTween = tween;
		}
		baseTween.start();
	},

	gridTween: function(pID, x, y, last) {
		var tween = game.add.tween(game.players[pID].sprite).to({'isoX': x * 100, 'isoY': y * 100}, 200, "Linear");
		tween.onComplete.add(function() {
			var nextXY = this.mapData.spaces[x + '-' + y].next.split('-');
			game.players[pID].sprite.scale.x = 1 * (nextXY[0] > x || nextXY[1] < y) ? 1 : -1;
			if (last) this.endMove(pID);
		}, this)
		return tween;
	},

	endMove: function(pID) {
		this.turnInProgress = false;
		this.cameraFollowLoose(game.players[pID].sprite);
		this.currPlayer += 1;
		if (this.currPlayer >= game.players.length) { this.currPlayer = 0;}
	},

	render: function() {
		
	},

	cameraFollowLoose(sprite) {
		game.camera.unfollow();
		game.add.existing(game.camera.pointTarget);
		game.camera.pointTarget.position = sprite.position.clone();
		game.camera.follow(game.camera.pointTarget, game.camera.FOLLOW_LOCKON, 0.3, 0.2)
	},

	cameraFollowTight(sprite) {
		game.camera.unfollow();
		sprite.addChild(game.camera.pointTarget);
		game.camera.pointTarget.position.setTo(0);
		game.camera.follow(game.camera.pointTarget, game.camera.FOLLOW_LOCKON, 0.6, 0.4);
	},

	postRoll: function() {
		if (this.turnInProgress === true) return;
		$.post({
			url: window.location +'/roll',
			context: this,
			success: function(rollData) {
				console.log(this.currPlayer, rollData);
				this.startMove(this.currPlayer, rollData.roll);
	    	}
		});
	},

	quitGame: function (pointer) {
		this.state.start('MainMenu');
	},
};
