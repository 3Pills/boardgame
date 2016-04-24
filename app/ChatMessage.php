<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chat_messages';

    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    

    /**
     * Hidden attributes that do not appear in arrays or JSON.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
