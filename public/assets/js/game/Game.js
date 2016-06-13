
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
		this.currTurn = 0;
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

		game.turns = [];
	},

	create: function () {
		this.tileGroup = game.add.group();
		this.plyGroup = game.add.group();
		this.HUDGroup = game.add.group();

		this.HUDGroup.fixedToCamera = true;

	    this.mapData = game.cache.getJSON('map_data');

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
    		tile.sprite = game.add.isoSprite(xy[0] * 100, xy[1] * 100, 0, 'base_tile', 0, this.tileGroup);
    		tile.sprite.anchor.set(0.5);
    		this.tileGroup.add(tile.sprite);

    		if (tile.subType === undefined) tile.subType = 0;
    		if (tile.type === 1) spawnCoords[tile.subType] = coord;

    		if (tileColors[tile.type][tile.subType] !== undefined) tile.sprite.tint = tileColors[tile.type][tile.subType];
		}
		game.iso.simpleSort(this.tileGroup);


        game.camera.pointTarget = game.add.sprite(0,0,'point',null, this.plyGroup);
        game.camera.pointTarget.anchor.set(0.5, 1);
        game.camera.pointTarget.renderable = false;
		game.camera.follow(game.camera.pointTarget, game.camera.FOLLOW_TOPDOWN_TIGHT, 0.3, 0.2)

		for (var i = game.players.length - 1; i >= 0; i--) {
			var xy = spawnCoords[i].split('-');
			var ply = game.players[i];

        	ply.turnOrder = i;
        	ply.currSpace = spawnCoords[i];

			ply.sprite = game.add.isoSprite(100 * xy[0],100 * xy[1], 0, ply.character + '_idle', 1, this.plyGroup);
			ply.sprite.anchor.set(0.5, 1);

			var nextXY = this.mapData.spaces[xy[0] + '-' + xy[1]].next.split('-');
			ply.sprite.scale.x = 1 * (nextXY[0] > xy[0] || nextXY[1] < xy[1]) ? 1 : -1;
			
			ply.sprite.animations.add(ply.character + '_idle', null,  game.cache.getJSON(ply.character + '_idle_atlas').meta.fps, true);
			ply.sprite.animations.play(ply.character + '_idle');

			ply.sprite.currCharacter = ply.character;
			ply.sprite.currPalette = ply.palette;

			ply.sprite.paletteData = game.cache.getJSON(ply.character + '_palettes');
        	ply.sprite.loadPalette(ply.character, '_idle');

        	ply.portraitBG = game.add.sprite((i%2 == 1) ? 1024 : 0, (i>=2) ? 576 : 0, 'card_portrait', null, this.HUDGroup);
			ply.portraitBG.scale.setTo((i%2 == 1) ? -2 : 2, (i>=2) ? -2: 2);

        	ply.portrait = game.add.sprite((i%2 == 1) ? 1024 - 8 : 8, (i>=2) ? 576 - 8 : 8, ply.character + '_portrait', null, this.HUDGroup)
        	ply.portrait.anchor.y = (i>=2) ? 1 : 0;
			ply.portrait.scale.setTo((i%2 == 1) ? -2 : 2, 2);

			ply.portrait.currCharacter = ply.character;
			ply.portrait.currPalette = ply.palette;

			ply.portrait.paletteData = game.cache.getJSON(ply.character + '_palettes');
        	ply.portrait.loadPalette(ply.character, '_portrait', false);

    		ply.textName = game.add.text((i%2 == 1) ? 1024 - 186 : 86, (i>=2) ? 506 : 10, ply.user_data.name, { font: "14px Arial", fill: "#ffffff", align: "left" }, this.HUDGroup);
    		ply.textName = game.add.text((i%2 == 1) ? 1024 - 186 : 86, (i>=2) ? 528 : 32, "Stars: 0/100", { font: "14px Arial", fill: "#ffffff", align: "left" }, this.HUDGroup);
    		ply.textName = game.add.text((i%2 == 1) ? 1024 - 186 : 86, (i>=2) ? 550 : 54, "HP: 5/5", { font: "14px Arial", fill: "#ffffff", align: "left" }, this.HUDGroup);

        	//Play the character's theme song, represented by their index number in the json file, starting at 1.
        	if (i == 0) {
        		//game.camera.playerTarget = count;
        		game.camera.pointTarget.position = ply.sprite.position.clone();
				game.sound.playMusic(ply.character+1);
        	}
		}

		var button = game.add.button(512, 526, 'roll_button', this.postRoll, this, 2, 1, 0, null, this.HUDGroup);
		button.anchor.set(0.5);
        //this.loadPalette(this.sprite, 'hisui-idle');

	    this.timers = {
	    	//getPlayerData: game.time.create(false).loop(1500, this.getPlayerData, this, 'state').timer
	    	getTurnData: game.time.create(false).loop(2000, this.getTurnData, this).timer,
	    } 
	    this.timers.getTurnData.start();
	    
    	game.input.addMoveCallback(this.moveMap, this);
	},

	moveMap: function(pointer, x, y, pressed) {
	    if (pointer.isDown) {
	    	if (pointer.prevPosition !== null) {
				game.camera.pointTarget.x += pointer.prevPosition.x - pointer.position.x;		
				game.camera.pointTarget.y += pointer.prevPosition.y - pointer.position.y;	
			}
			pointer.prevPosition = pointer.position.clone();
		}
	},

	update: function () {
		if (!game.input.activePointer.isDown) {
			game.input.activePointer.prevPosition = null;
		}
		game.iso.simpleSort(this.plyGroup);
		//this.updatePlayers();
	},
	
	startMove: function(pID, amount) {
		this.currPlayer = pID;
		console.log(this.currTurn, this.currPlayer, game.turns[this.currTurn], amount);
		var ply = game.players[pID];
		this.turnInProgress = true;
		this.timers.getTurnData.pause();
		//this.cameraFollowTight(ply.sprite);
		var prevTween = undefined;
		for (var i = amount-1; i >= 0; i--) {
			var nextXY = this.mapData.spaces[ply.currSpace].next.split('-');
			var tween = this.gridTween( pID, Number(nextXY[0]), Number(nextXY[1]), (i == 0) );
			ply.currSpace = this.mapData.spaces[ply.currSpace].next;

			if (prevTween !== undefined) prevTween.chain(tween);
			prevTween = tween;

			if (i==amount-1) tween.start();
		}
	},

	gridTween: function(pID, x, y, last) {
		var ply = game.players[pID];
		var tween = game.add.tween(ply.sprite).to({'isoX': x * 100, 'isoY': y * 100}, 200, "Linear");
		tween.onComplete.add(function() {
			var nextXY = this.mapData.spaces[x + '-' + y].next.split('-');
			ply.sprite.scale.x = 1 * (nextXY[0] > x || nextXY[1] < y) ? 1 : -1;
			if (last) this.endMove(pID);
		}, this)
		return tween;
	},

	endMove: function(pID) {
		this.currTurn += 1;
		this.turnInProgress = false;
		this.timers.getTurnData.resume();
		//this.cameraFollowLoose(game.players[pID].sprite);
		console.log("huh", game.players.length);
		if (game.turns[this.currTurn]) {
			for (var pID in game.players) {
				if (game.players[pID].user_data.id == game.turns[this.currTurn].user_id) {
					this.startMove(Number(pID), game.turns[this.currTurn].data.roll);
				}
			}
			
		}
	},

	render: function() {
		
	},

	getPlayersOnSpace: function (coords) {
		//for (var pID in )
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
		if (this.turnInProgress == true) return;
		$.post({
			url: window.location +'/roll',
			context: this,
			success: function(rollData) {
				this.startMove(this.currPlayer, rollData.roll);
	    	}
		});
	},

	getTurnData: function() {
		$.get({
			url: window.location+'/tData',
			context: this,
			data: {tID: game.turns.length == 0 ? 0 : game.turns[game.turns.length-1].id },
			success: function(data) {
				if (Object.keys(data).length > 0) {
					for (var tID in data.turns) {
						game.turns.push(data.turns[tID]);
						if (this.currTurn == game.turns.length-1) {
							for (var pID in game.players) {
								if (game.players[pID].user_data.id == data.turns[tID].user_id) {
									this.currPlayer = Number(pID);
								}
							}
							switch(data.turns[tID].data.type) {
								case 1:
									this.startMove(this.currPlayer, data.turns[tID].data.roll);
									break;
							}
						}
					}
				}
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

	quitGame: function (pointer) {
		this.state.start('MainMenu');
	},
};
