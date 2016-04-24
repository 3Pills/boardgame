<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Fillable attributes that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'biography', 'url', 'about',
    ];

    /**
     * Hidden attributes that do not appear in arrays or JSON.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Scope queries to users that are admins.
     *
     * @param $query
     */
    public function scopeAdmin($query) {
        $query->where('is_admin', '=', true);
    }

    /**
     * A user can have a player record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function player() {
        return $this->hasOne('App\Player');
    }

    public function setUrlAttribute($url) {
        $url = strtolower($url);
        if (in_array('url', $this->attributes) && (file_exists('./images/avatars/'.$this->attributes['url'].'.jpg'))) {
            rename(url('/').'/images/avatars/'.$this->attributes['url'].'.jpg', url('/').'/images/avatars/'.$url.'.jpg');
        }        
        $this->attributes['url'] = $url;
    }

    //public function setPasswordAttribute($password) {
    //    $this->attributes['password'] = bcrypt($password);
    //}
}