var BoardGame = {};

BoardGame.Boot = function (game) {

};

BoardGame.Boot.prototype = {

    init: function () {
        game.input.maxPointers = 1;
        game.stage.disableVisibilityChange = true;

        game.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;
        game.scale.fullScreenScaleMode = Phaser.ScaleManager.SHOW_ALL;
        game.scale.fullScreenTarget = $('.container-game').get(0);
        game.scale.onFullScreenChange.add(this.toggleFullscreen, this);

        game.state.add('Preloader', BoardGame.Preloader);
        game.state.add('MainMenu', BoardGame.MainMenu);
        game.state.add('GameLoader', BoardGame.GameLoader);
        game.state.add('Game', BoardGame.Game);
        game.state.add('GameFinish', BoardGame.GameFinish);

        game.sound.sound_vol = 0.5;
        game.sound.music_vol = 0.0;

        game.canvas.oncontextmenu = function (e) { e.preventDefault(); }
    },

    preload: function () {
        //  Here we load the assets required for our preloader (in this case a background and a loading bar)
        game.load.image('preloadBackground', '../assets/sprites/preload_bg.png');
        game.load.image('preloadBar', '../assets/sprites/title_card.png');
        
        var music_url = '../assets/audio/music/' + 'lb/';
        game.load.json('music_data', music_url +'/data.json');
        game.load.json('character_data', '../assets/sprites/characters.json');
    },

    create: function () {
        this.state.start('Preloader');
    },

    toggleFullscreen: function() {
        $('.container-game').toggleClass('fullscreen');
        $('.container-game').toggleClass('docked');
        $('.game-overlay-fullscreen').find( 'glyphicon' ).toggleClass('glyphicon-resize-full');
        $('.game-overlay-fullscreen').find( 'glyphicon' ).toggleClass('glyphicon-resize-small');
    }
};
