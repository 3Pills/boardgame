<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameTurns extends Model {
    /**
     * Defines whether the model will use automatic timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Boot function for Eloquent Model.
     *
     * @return
     */
    public static function boot() {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

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
        'user_id', 'data',
    ];

}
