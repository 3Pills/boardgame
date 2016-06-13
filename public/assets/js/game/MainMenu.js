
BoardGame.MainMenu = function (game) {};

BoardGame.MainMenu.prototype = {

	create: function () {
		game.stage.backgroundColor = '#5577FF';
		game.players = [];
		//game.world.setBounds(0, 0, 1920, 1920);
		
		this.sprite = game.add.sprite(512, 375,'0_idle');
		this.sprite.anchor.set(0.5, 1);
		
		this.sprite.animations.add('0_idle', null, game.cache.getJSON('0_idle_atlas').meta.fps, true);
		this.sprite.animations.play('0_idle');

		this.sprite.currCharacter = 0;
		this.sprite.currPalette = 0;

		this.characterData = game.cache.getJSON('character_data');
		this.sprite.paletteData = game.cache.getJSON('0_palettes');

		this.sprite.inputEnabled = true;
		this.sprite.input.useHandCursor = true;
		this.sprite.events.onInputDown.add(this.cycleSpriteColor, this, 0, this.sprite, true);

		var count = 0;
		for (var i = game.players.length - 1; i >= 0; i--) {
			var ply = ply;
			ply.sprite = game.add.sprite(320 + 128 * count, 400, ply.character + '_idle');
			ply.sprite.anchor.set(0.5, 1);
			
			ply.sprite.animations.add(ply.character + '_idle', null, 20, true);
			ply.sprite.animations.play(ply.character + '_idle');

			ply.sprite.currCharacter = ply.character;
			ply.sprite.currPalette = ply.palette;

			ply.sprite.paletteData = game.cache.getJSON(ply.character + '_palettes');
        	ply.sprite.loadPalette(ply.character, '_idle');
        	count += 1;
		}

		/*
		this.vol_slider = game.add.sprite(40,40,'slider_tab');
		this.vol_slider.inputEnabled = true;
		this.vol_slider.input.useHandCursor = true;

		this.vol_slider.width = 32;
		this.vol_slider.height = 32;
		this.vol_slider.anchor.set(0.5);

		this.vol_slider.events.onInputDown.add(function(sprite) { sprite.moving = true; }, this);
		this.vol_slider.events.onInputUp.add(function(sprite) { sprite.moving = false; }, this);

		this.vol_slider.fixedToCamera = true;
		this.vol_slider.cameraOffset.set(game.scale.width / 2, 100);

	    game.input.addMoveCallback(function(ptr, x, y, down) {
	    	if (this.vol_slider.moving == true) {

				var offsetMinMax = game.scale.width / 5;
				x = game.math.clamp(x, offsetMinMax, game.scale.width - offsetMinMax);
				var vol = game.math.roundTo(game.math.clamp((x - offsetMinMax) / (game.scale.width - offsetMinMax - offsetMinMax), 0, 1), -3);

				if (this.bgm_loop) {
					this.bgm_loop.volume = vol;
					this.bgm_loop.mute = (vol == 0)
				}
				this.vol_slider.cameraOffset.x = x;
	    	}
	    	this.vol_slider.prevX = this.vol_slider.cameraOffset.x;
	    }, this);
	    */

	    //game.camera.follow(this.vol_slider);
		

		this.button = game.add.button(512, 476, 'ready_button', this.postReady, this, 2, 1, 0)
		this.button.anchor.set(0.5);

		//Let the server know we've joined the game.
		//$.post({ url: window.location+'/enter' });

		//var sprite = game.add.sprite(200,200,'hisui');

		this.inputKeys = game.input.keyboard.addKeys({
			'up': Phaser.Keyboard.UP,
			'dn': Phaser.Keyboard.DOWN,
			'lf': Phaser.Keyboard.LEFT,
			'rt': Phaser.Keyboard.RIGHT,
			//'enter': Phaser.KeyCode.ENTER, 
			//'backspace': Phaser.KeyCode.BACKSPACE
		});
		
		this.inputKeys.up.onDown.add(this.cycleSpriteColor, this, 0, this.sprite, false);
		this.inputKeys.dn.onDown.add(this.cycleSpriteColor, this, 0, this.sprite, true);
		
		this.inputKeys.lf.onDown.add(this.cycleSprite, this, 0, this.sprite, false);
		this.inputKeys.rt.onDown.add(this.cycleSprite, this, 0, this.sprite, true);

		/*
		this.chatString = "";
		this.chatResponse = ["Welcome to the chat!"];

		game.input.keyboard.onPressCallback = this.chat_key;

		this.inputKeys.enter.onDown.add(this.postChat, this);
		this.inputKeys.backspace.onDown.add(this.chat_backspace, this);
		game.input.keyboard.addKeyCapture([Phaser.KeyCode.BACKSPACE, Phaser.KeyCode.ENTER])
		
		this.playLoop('bgm1_start', 'bgm1_loop');
		this.playLoop('bgm2_start', 'bgm2_loop');
		*/
	    //UTC time of the last chat message received. Used to compare messages when pinging database.
	    //this.latest_chat = moment.utc(0); 

	    this.latest = {
	    	'chat': 0,
	    	'pList': 0,
	    };

		this.joinCountText  = game.add.text(32, 20, "Status: Haven't Joined", { font: "12px Arial", fill: "#ffffff", align: "left" });
		this.joinPlayerText = game.add.text(32, 50, "List of Players:\n", { font: "12px Arial", fill: "#ffffff", align: "left" });

		this.button2 = game.add.button(256, 476, 'join_button', this.postJoin, this, 2, 1, 0);
		this.button2.anchor.set(0.5);

		game.sound.playMusic(0);

	    //  Create our Timer
	    this.timers = {
	    	getPlayerList: game.time.create(false).loop(2000, this.getPlayerList, this).timer,
	    	getPlayerData: game.time.create(false).loop(1500, this.getPlayerData, this, 'state').timer
	    } 
	    this.getPlayerList();
	    this.timers.getPlayerList.start();
	},

	update: function () {
		//game.input.enabled = game.input.activePointer.withinGame;
	},
	
	render: function () {
		/*
        game.debug.start(32, 20);

        game.debug.line('fps: '+game.time.fps)

        game.debug.line('');

		if (this.bgm_loop) {
	        game.debug.line('Sound: ' + this.bgm_loop.key + ' Total Duration: ' + this.bgm_loop.totalDuration);
	        game.debug.line('Time: ' + this.bgm_loop.currentTime);
	        game.debug.line('Volume: ' + this.bgm_loop.volume + ' Muted: ' + this.bgm_loop.mute);
	    }

        game.debug.line('');
        game.debug.line('Random Roll: ' + this.randNumb);

        game.debug.line('');
        game.debug.line('Chat: ' + this.chatString);
        for (let msg of this.chatResponse) {
        	game.debug.line(msg.time + ' - ' + msg.name + ': ' + msg.msg);
        }

        game.debug.stop();

	    game.debug.cameraInfo(game.camera, 32, 32);
	    game.debug.soundInfo(this.bgm_loop, 32, 180);
	    game.debug.spriteInfo(this.sprite, 32, 350);
	    game.debug.spriteCoords(this.vol_slider, 32, 500);

	    game.debug.text(, 32, 20);
	    */
	},

	resize: function (width, height) {
		//	If the game container is resized this function will be called automatically.
		//	You can use it to align sprites that should be fixed in place and other responsive display things.
	},

	startGame: function() {
		if (game.players.length == 0) { return; }
		this.state.start('GameLoader');
	},

	postChat: function() {
		if (this.chatString.length > 0) {
			var msg = this.chatString;
			this.chatString = "";
			$.post({
				url: window.location+'/chat',
				data: {msg: msg},
				context: this,
				success: function(data) {
					this.chatResponse[this.chatResponse.length] = {
						id: -1,
						time: new Date().toLocaleTimeString(),
						name: data,
						msg: msg
					}
		    	}
			});
			this.get_chat();
		}
	},

	getPlayerList: function() {
		var uIDs = [];
		for (var pID in game.players) {
			uIDs.push(game.players[pID].user_data.id);
		}
		$.get({
			url: window.location+'/pList',
			context: this,
			data: {uIDs: uIDs},
			success: function(data) {
				if (Object.keys(data).length > 0) {
					this.latest.pList = data.time;
					game.players = game.players.concat(data.players);
					//game.players.sort(function(a, b) {return moment.utc(a.created_at).valueOf() > moment.utc(b.created_at).valueOf()})
					this.joinPlayerText.text = "List of Players:\n";
					for (var pID in game.players) {
						this.joinPlayerText.text = this.joinPlayerText.text + game.players[pID].user_data.name + '\n';
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

	successfulUpdate: function(key) {
		switch(key) {
			case 'state':
				var notReady = false;
				if (game.players.length <= 1) return;
				this.joinCountText.text = "Waiting for other players to ready up... (";
				for (var i = game.players.length - 1; i >= 0; i--) {
					if (game.players[i].state < 1) {
						notReady = true;
						this.joinCountText.text += game.players[i].user_data.name + ", ";
					}
				}
				this.joinCountText.text += ")";

				if (notReady) return;
				this.joinCountText.text = "Status: Everyone's Ready! Starting in 3 seconds..."

				game.time.events.add(Phaser.Timer.SECOND * 0.1, function() { this.state.start('GameLoader'); }, this);
				break;
			default:
				break;
		}
	},

	postJoin: function() {
		$.post({
			url: window.location+'/join',
			data: {character: this.sprite.currCharacter, palette: this.sprite.currPalette},
			context: this,
			success: function(data) {
				if (data.status == 1) this.joinCountText.text = "Status: Joined the game! Be sure to ready up once you're happy with your character, otherwise, change the character and click the join button again."
				else if (data.status == 2) this.joinCountText.text = "Status: Character updated! Don't keep the others waiting...";
				else this.joinCountText.text = "Status: Game is full or you've already joined it!"
			}
		});
	},

	postReady: function() {
		$.post({
			url: window.location+'/ready',
			data: {},
			context: this,
			success: function(data) {
				this.joinCountText.text = "Status: Ready! Checking on other users statuses..."
	   			this.timers.getPlayerData.start();
			}
		});
	},

	postCharacter: function() {
		$.post({
			url: window.location+'/character',
			context: this,
			success: function(data) {
				this.randNumb = data;
	    	}
		});
	},
	
	cycleSprite: function(key, sprite, upward) {
		if (!sprite.paletteData) { return; }
		sprite.currCharacter = sprite.currCharacter + (upward ? 1 : -1);
		sprite.currCharacter = ( sprite.currCharacter < 0 ) ? this.characterData.length-1 : ( ( sprite.currCharacter >= this.characterData.length ) ? 0 : sprite.currCharacter );
		sprite.currPalette = 0;

		sprite.paletteData = game.cache.getJSON(sprite.currCharacter + '_palettes');
		sprite.loadPalette(sprite.currCharacter, '_idle', true);
	},
	
	cycleSpriteColor: function(key, sprite, upward) {
		if (!sprite.paletteData) { return; }
		sprite.currPalette = sprite.currPalette + (upward ? 1 : -1);
		sprite.currPalette = ( sprite.currPalette < 0 ) ? sprite.paletteData.length-1 : ( ( sprite.currPalette >= sprite.paletteData.length ) ? 0 : sprite.currPalette );
		sprite.loadPalette(sprite.currCharacter, '_idle');
	},
};