<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Sessions extends Model {
    public $table = 'sessions';
    public $timestamps = false;

    /**
     * Returns the user that belongs to this entry.
     *
     * @return \Cartalyst\Sentinel\Users\EloquentUser
     */
    public function user() {
        return $this->belongsTo('Cartalyst\Sentinel\Users\EloquentUser');
    }

    /**
     * Returns all the users within the given activity.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivity($query, $limit = 10) {
        $lastActivity = strtotime(Carbon::now()->subMinutes($limit));
        return $query->where('last_activity', '>=', $lastActivity);
    }

    /**
     * Returns all the guest users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGuests(Builder $query) {
        return $query->whereNull('user_id');
    }

    /**
     * Returns all the registered users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegistered(Builder $query) {
        return $query->whereNotNull('user_id')->with('user');
    }

    /**
     * Updates the session of the current user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdateCurrent(Builder $query) {
        $user = Sentinel::check();

        return $query->where('id', Session::getId())->update([
            'user_id' => $user ? $user->id : null
        ]);
    }
}
