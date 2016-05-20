<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'players';

    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [ 
        'game_id', 'user_id'
    ];


    /**
     * Hidden attributes that do not appear in arrays or JSON.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * A player can join a single game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function game() {
        return $this->belongsTo('App\Game');
    }

    /**
     * A player can have a single user account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
}
