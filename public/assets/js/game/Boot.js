var BoardGame = {};

BoardGame.Boot = function (game) {

};

BoardGame.Boot.prototype = {

    init: function () {
        this.input.maxPointers = 1;
        this.stage.disableVisibilityChange = true;
        this.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;

        game.state.add('Preloader', BoardGame.Preloader);
        game.state.add('MainMenu', BoardGame.MainMenu);
        game.state.add('GameLoader', BoardGame.GameLoader);
        game.state.add('Game', BoardGame.Game);
        game.state.add('GameFinish', BoardGame.GameFinish);

        game.sound.sound_vol = 0.5;
        game.sound.music_vol = 0.1;

        game.canvas.oncontextmenu = function (e) { e.preventDefault(); }
    },

    preload: function () {
        //  Here we load the assets required for our preloader (in this case a background and a loading bar)
        game.load.image('preloadBackground', base_url + 'assets/sprites/preload_bg.png');
        game.load.image('preloadBar', base_url + 'assets/sprites/title_card.png');
        
        game.load.json('music-data', music_url +'/data.json');
    },

    create: function () {
        this.state.start('Preloader');
    }

};
