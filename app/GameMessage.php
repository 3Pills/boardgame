<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameMessage extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'game_messages';
    
    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'msg', 'user_id', 'game_id',
    ];

    /**
     * A message will be part of a game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function game() {
        return $this->belongsTo('App\Game');
    }
}
