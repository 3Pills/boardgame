<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameTurns extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'game_turns';
    
    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'biography', 'image',
    ];

}
