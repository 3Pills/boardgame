<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'games';
    
    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url', 'map', 'private',
    ];

    /**
     * A game can have many players.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players() {
        return $this->hasMany('App\Player');
    }
}
