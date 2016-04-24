
BasicGame.MainMenu = function (game) {
	this.game = game;
};

BasicGame.MainMenu.prototype = {
	playloop: function(sound1, sound2) {
		if (this.bgm_start) {

		}
		if (this.bgm_loop) {

		}

		if (sound2 != null) {
			this.bgm_start = game.add.audio(sound1);

			this.bgm_loop = game.add.audio(sound2);
		
			game.sound.setDecodedCallback([this.bgm_start, this.bgm_loop], function() {
				this.bgm_start.onStop.add(function() {
					this.bgm_loop.loopFull();
				}, this)
				this.bgm_start.play(); 
			}, this);
			return this.bgm_start;
		}
		else {
			this.bgm_loop = game.add.audio(sound1);
			this.bgm_loop.loopFull();
			return this.bgm_loop;
		}
	},
	
	swappalette: function(bmd, pID) {
		bmd.load('hisui'); //Reload the original palette. Stops colors becoming 'watered down' due to duplication, eventually.
		
		//Optimisation of rgb replacement. Is only done for differing values, as a palette may contain duplicates.
		//Duplicates are determined via the original palette. Custom palettes may merge colors, causing duplication checks to pointlessly pass.
		if (bmd.nonDupes) { 
			for (var slot in bmd.nonDupes) {
				slot = bmd.nonDupes[slot];
				//Only replace colors that are not identical.
				if (bmd.pData[0][slot].join() != bmd.pData[pID][slot].join()) {
					bmd.replaceRGB(bmd.pData[0][slot][0],bmd.pData[0][slot][1],bmd.pData[0][slot][2],255,bmd.pData[pID][slot][0],bmd.pData[pID][slot][1],bmd.pData[pID][slot][2],255);
				}
			}
		}
		else { 
			//Store each different pixel color, so duplicates are not replaced after one iteration.
			bmd.nonDupes = []; 
			for (var i = bmd.pData[0].length; i--;) {
				var joined = bmd.pData[0][i].join();
				if (bmd.nonDupes[joined] == undefined) {
					bmd.nonDupes[joined] = i;
					if (bmd.pData[0][i].join() != bmd.pData[pID][i].join()) {
						bmd.replaceRGB(bmd.pData[0][i][0],bmd.pData[0][i][1],bmd.pData[0][i][2],255,bmd.pData[pID][i][0],bmd.pData[pID][i][1],bmd.pData[pID][i][2],255);
					}
				}
			}
		}
	},
	
	create: function () {
		game.stage.backgroundColor = '#5577FF';
		//game.world.setBounds(0, 0, 1920, 1920);
		
		this.bmd = game.make.bitmapData();
		//var atlasData = { frames: [] };
		this.bmd.load('hisui');
		this.bmd.pData = game.cache.getJSON('hisui-palettes');
		//bmd.replaceRGB(col[0][5][0],col[0][5][1],col[0][5][2],255,col[1][5][0],col[1][5][1],col[1][5][2],255);
		
		this.pCount = 0;
		//this.swappalette(this.bmd, 30);
		this.bmd.atlasData = game.cache.getJSON('hisui-atlas');
		
		//console.log(atlasData);
		game.cache.addTextureAtlas('hisui-colored', null, this.bmd.canvas, this.bmd.atlasData, Phaser.Loader.TEXTURE_ATLAS_JSON_HASH);

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
	    		//this.vol_slider_tween.stop();
	    		//this.vol_slider_tween.to({x:x}, 100, Phaser.Easing.Linear.None, true);
				this.vol_slider.cameraOffset.x = x;
	    	}
	    	this.vol_slider.prevX = this.vol_slider.cameraOffset.x;
	    }, this);

	    //game.camera.follow(this.vol_slider);
		
		this.sprite = game.add.sprite(game.scale.width / 2,game.scale.height / 2,'hisui-colored');
		this.sprite.anchor.set(0.5);

		this.button = game.add.button(game.scale.width / 2, game.scale.height - 100, 'hisui-colored', function(){
			$.post({
				url: window.location+'/roll',
				context: this,
				success: function(data) {
					this.randNumb = data;
		    	}
			});
		}, this, 2, 3, 4, 5)

		//Let the server know we've joined the game.
		//$.post({ url: window.location+'/enter' });

		//var sprite = game.add.sprite(200,200,'hisui');
		
		this.sprite.animations.add('idle', null, 20, true);
		this.sprite.animations.play('idle');
		this.sprite.inputEnabled = true;
		this.sprite.input.useHandCursor = true;

		this.sprite.events.onInputDown.add(this.cycleSpriteColor, this, 0, this.sprite, true);
		
		this.inputKeys = game.input.keyboard.addKeys({
			'up': Phaser.Keyboard.UP,
			'down': Phaser.Keyboard.DOWN,
			'left': Phaser.Keyboard.LEFT,
			'right': Phaser.Keyboard.RIGHT,
			'enter': Phaser.KeyCode.ENTER, 
			'backspace': Phaser.KeyCode.BACKSPACE
		});

		this.chatString = "";
		this.chatResponse = ["Welcome to the chat!"];

		game.input.keyboard.onPressCallback = this.chat_key;
		this.inputKeys.enter.onDown.add(this.chat_enter, this);
		this.inputKeys.backspace.onDown.add(this.chat_backspace, this);
		game.input.keyboard.addKeyCapture([Phaser.KeyCode.BACKSPACE, Phaser.KeyCode.ENTER])

	    //  Create our Timer
	    this.timers = {
	    	chat_ajax: game.time.create(false).loop(2000, this.chat_update, this).timer
	    } 
	    this.timers.chat_ajax.start();

	    //UTC time of the last chat message received. Used to compare messages when pinging database.
	    this.latest_chat = moment(0).utc().format(); 
		
		this.inputKeys.left.onDown.add(this.cycleSpriteColor, this, 0, this.sprite, true);
		this.inputKeys.right.onDown.add(this.cycleSpriteColor, this, 0, this.sprite, false);
		//this.playloop('bgm1_start', 'bgm1_loop');
		//this.playloop('bgm2_start', 'bgm2_loop');
		//this.playloop('bgm3_loop');
	},

	chat_key: function(key) {
		if (game.state.getCurrentState().chatString.length < 64) {
			game.state.getCurrentState().chatString = game.state.getCurrentState().chatString + key;
		}
	},

	chat_enter: function() {
		if (this.chatString.length > 0) {
			var msg = this.chatString;
			this.chatString = "";
			$.post({
				url: window.location+'/chat',
				data: {msg: msg},
				context: this,
				success: function(data) {
					this.chatResponse[this.chatResponse.length] = new Date().toLocaleTimeString() + " - " + data + ": " + msg;
		    	}
			});
			this.chat_update();
		}
	},

	chat_update: function() {
		$.post({
			url: window.location+'/getChat',
			data: {latest_chat : this.latest_chat},
			context: this,
			success: function(data) {
				for (var msg in data) {
					if (!isNaN(msg)) {
						this.latest_chat = data[msg].time;
						this.chatResponse[this.chatResponse.length] = moment.utc(data[msg].created_at).toDate().toLocaleTimeString() + " - " + data.user_names[data[msg].user_id] + ": " + data[msg].msg;
						if (this.chatResponse.length > 10){
							this.chatResponse.shift();
						}
					}
				}
	    	}
		});
	},

	chat_backspace: function() {
		if (this.chatString.length > 0) {
			this.chatString = this.chatString.substring(0,this.chatString.length-1);
		}
	},
	
	cycleSpriteColor: function(key, sprite, upward) {
		var x = this.sprite.worldPosition.x, y = this.sprite.worldPosition.y;
		var currAnim = this.sprite.animations.currentAnim.name;
		var currFrame = this.sprite.animations.currentAnim.currentFrame.index;

		this.pCount = this.pCount + (upward ? 1 : -1);
		this.pCount = ( this.pCount < 0 ) ? this.bmd.pData.length-1 : ( ( this.pCount >= this.bmd.pData.length ) ? 0 : this.pCount );
		this.swappalette(this.bmd, this.pCount);

		game.cache.addTextureAtlas('hisui-colored', null, this.bmd.canvas, this.bmd.atlasData, Phaser.Loader.TEXTURE_ATLAS_JSON_HASH);
		this.sprite.loadTexture('hisui-colored', this.sprite.animations.currentAnim.currentFrame.index, false);
	},

	update: function () {
		game.input.enabled = game.input.activePointer.withinGame;

		var offsetMinMax = 200;
		this.vol_slider.cameraOffset.x = game.math.clamp(this.vol_slider.cameraOffset.x, offsetMinMax, game.scale.width - offsetMinMax);
		var volRatio = (this.vol_slider.cameraOffset.x - offsetMinMax) / (game.scale.width - offsetMinMax - offsetMinMax);
		var vol = game.math.roundTo(isFinite(volRatio) ? game.math.clamp(volRatio, 0, 1) : 0, -3);

		if (this.bgm_loop) {
			this.bgm_loop.volume = vol;
			this.bgm_loop.mute = (vol == 0)
		}
		//if (typeof this.bgm_start !== undefined) {
		//	this.bgm_start.volume = vol;
		//}
	},
	
	render: function () {
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
        	game.debug.line(msg);
        }

        game.debug.stop();

	    //game.debug.cameraInfo(game.camera, 32, 32);
	    //game.debug.soundInfo(this.bgm_loop, 32, 180);
	    //game.debug.spriteInfo(this.sprite, 32, 350);
	    //game.debug.spriteCoords(this.vol_slider, 32, 500);
//
	    //game.debug.text(, 32, 20);
	},

	resize: function (width, height) {

		//	If the game container is resized this function will be called automatically.
		//	You can use it to align sprites that should be fixed in place and other responsive display things.

	    // this.bg.width = width;
	    // this.bg.height = height;

	    // this.spriteMiddle.x = game.world.centerX;
	    // this.spriteMiddle.y = game.world.centerY;
        // 
	    // this.spriteTopRight.x = game.width;
	    // this.spriteBottomLeft.y = game.height;
        // 
	    // this.spriteBottomRight.x = game.width;
	    // this.spriteBottomRight.y = game.height;

	}

};