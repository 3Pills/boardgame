var BasicGame = {};

BasicGame.Boot = function (game) {

};

BasicGame.Boot.prototype = {

    init: function () {
        game.input.maxPointers = 1;
        game.stage.disableVisibilityChange = true;
        game.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;

        game.state.add('Preloader', BasicGame.Preloader);
        game.state.add('MainMenu', BasicGame.MainMenu);
        game.state.add('Game', BasicGame.Game);

        game.canvas.oncontextmenu = function (e) { e.preventDefault(); }
    },

    preload: function () {
        //  Here we load the assets required for our preloader (in this case a background and a loading bar)
        game.load.image('preloadBackground', base_url + 'assets/sprites/preload_bg.png');
        game.load.image('preloadBar', base_url + 'assets/sprites/title_card.png');
    },

    create: function () {
        game.state.start('Preloader');
    }

};
