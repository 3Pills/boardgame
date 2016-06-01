
BoardGame.MainMenu = function (game) {};

BoardGame.MainMenu.prototype = {

	create: function () {
		game.stage.backgroundColor = '#5577FF';
		//game.world.setBounds(0, 0, 1920, 1920);
		
		this.sprite = game.add.sprite(game.scale.width / 2,game.scale.height / 2,'hisui-idle');
		this.sprite.anchor.set(0.5);
		
		this.sprite.animations.add('idle', null, 20, true);
		this.sprite.animations.play('idle');

		this.sprite.currPalette = 0;
		this.sprite.paletteData = game.cache.getJSON('hisui-palettes');
		this.sprite.atlasData = game.cache.getJSON('hisui-atlas');

		this.sprite.inputEnabled = true;
		this.sprite.input.useHandCursor = true;
		this.sprite.events.onInputDown.add(this.cycleSpriteColor, this, 0, this.sprite, true);

		this.joinCount = 0;

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
		

		this.button = game.add.button(game.scale.width / 2, game.scale.height - 100, 'start-button', this.postRoll, this, 1, 0, 2)
		this.button.anchor.set(0.5);

		this.button.onInputUp.add(this.startGame, this);

		//Let the server know we've joined the game.
		//$.post({ url: window.location+'/enter' });

		//var sprite = game.add.sprite(200,200,'hisui');

		this.inputKeys = game.input.keyboard.addKeys({
			'up': Phaser.Keyboard.UP,
			'down': Phaser.Keyboard.DOWN,
			'left': Phaser.Keyboard.LEFT,
			'right': Phaser.Keyboard.RIGHT,
			//'enter': Phaser.KeyCode.ENTER, 
			//'backspace': Phaser.KeyCode.BACKSPACE
		});

		this.chatString = "";
		this.chatResponse = ["Welcome to the chat!"];

		game.input.keyboard.onPressCallback = this.chat_key;

		/*
		this.inputKeys.enter.onDown.add(this.postChat, this);
		this.inputKeys.backspace.onDown.add(this.chat_backspace, this);
		game.input.keyboard.addKeyCapture([Phaser.KeyCode.BACKSPACE, Phaser.KeyCode.ENTER])

	    //  Create our Timer
	    this.timers = {
	    	chat_ajax: game.time.create(false).loop(2000, this.get_chat, this).timer
	    } 
	    this.timers.chat_ajax.start();
	    //UTC time of the last chat message received. Used to compare messages when pinging database.
	    this.latest_chat = moment.utc(0).format(); 
		*/
		
		this.inputKeys.up.onDown.add(this.cycleSpriteColor, this, 0, this.sprite, false);
		this.inputKeys.down.onDown.add(this.cycleSpriteColor, this, 0, this.sprite, true);
		//this.playLoop('bgm1_start', 'bgm1_loop');
		//this.playLoop('bgm2_start', 'bgm2_loop');

		this.playLoop(0);
	},

	update: function () {
		game.input.enabled = game.input.activePointer.withinGame;
		if (game.sound.music_vol !== undefined) {
			if (this.bgm_start !== undefined)
				this.bgm_start.volume = game.sound.music_vol;
			if (this.bgm_loop !== undefined)
				this.bgm_loop.volume = game.sound.music_vol;
		}
	},
	
	render: function () {
        game.debug.start(32, 20);
        game.debug.line('Users joined: ' + this.joinCount);
        game.debug.stop();
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
		if (this.bgm_start !== undefined)
			this.bgm_start.stop();
		if (this.bgm_loop !== undefined)
			this.bgm_loop.stop();
		this.state.start('GameLoader', true, false, { character:0, palette:this.sprite.currPalette });
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

	postJoin: function() {
		$.post({
			url: window.location+'/join',
			data: {stage : 1},
			context: this,
			success: function(data) {
				this.joinCount = data.joined;
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

	postRoll: function() {
		$.post({
			url: window.location+'/roll',
			context: this,
			success: function(data) {
				this.randNumb = data;
	    	}
		});
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
	
	cycleSpriteColor: function(key, sprite, upward) {
		if (!sprite.paletteData) { return; }
		sprite.currPalette = sprite.currPalette + (upward ? 1 : -1);
		sprite.currPalette = ( sprite.currPalette < 0 ) ? sprite.paletteData.length-1 : ( ( sprite.currPalette >= sprite.paletteData.length ) ? 0 : sprite.currPalette );
		this.loadPalette(sprite, 'hisui-idle');
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