<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVerify extends Model {
    /**
     * Defines whether the model will use automatic timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_verification';

    /**
     * Boot function for Eloquent Model.
     *
     * @return
     */
    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token'
    ];
}
