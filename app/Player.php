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

    
    protected $primaryKey = 'user_id';

    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [ 
        'game_id', 'user_id', 'state', 'character', 'palette'
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

    /**
     * Scope players that have been recently updated in a specfic game.
     *
     * @param $query
     */
    public function scopeNotPartOf($query, $uIDs, $game_id) {
        return $query->whereNotIn('user_id', $uIDs)->where('game_id', '=', $game_id);
    }

    /**
     * Scope players that have been recently updated in a specfic game.
     *
     * @param $query
     */
    public function scopeRecentlyCreated($query, $time, $game_id) {
        return $query->where('created_at', '>', $time)->where('game_id', '=', $game_id);
    }

    /**
     * Scope players that have been recently updated in a specfic game.
     *
     * @param $query
     */
    public function scopeRecentlyUpdated($query, $time, $game_id) {
        return $query->where('updated_at', '>', $time)->where('game_id', '=', $game_id);
    }

    /**
     * Scope players that have been recently updated in a specfic game.
     *
     * @param $query
     */
    public function scopeRecentlyCreatedUser($query, $time, $game_id, $user_id) {
        return $query->where('created_at', '>', $time)->where('game_id', '=', $game_id)->where('user_id', '=', $user_id);
    }

    /**
     * Scope players that have been recently updated in a specfic game.
     *
     * @param $query
     */
    public function scopeRecentlyUpdatedUser($query, $time, $game_id, $user_id) {
        return $query->where('updated_at', '>', $time)->where('game_id', '=', $game_id)->where('user_id', '=', $user_id);
    }
}
