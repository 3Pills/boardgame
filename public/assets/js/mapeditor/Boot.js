var MapEditor = {};

MapEditor.Boot = function (game) {

};

MapEditor.Boot.prototype = {

    init: function () {
        this.input.maxPointers = 1;
        this.stage.disableVisibilityChange = true;
        this.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;

        game.state.add('Preloader', MapEditor.Preloader);
        game.state.add('Main', MapEditor.Main);

        game.canvas.oncontextmenu = function (e) { e.preventDefault(); }
    },

    preload: function () {
        //  Here we load the assets required for our preloader (in this case a background and a loading bar)
        this.load.image('preloadBackground', base_url + 'assets/sprites/preload_bg.png');
        this.load.image('preloadBar', base_url + 'assets/sprites/title_card.png');
    },

    create: function () {
        this.state.start('Preloader');
    }

};
