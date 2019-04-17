<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed id
 */
class User extends Authenticatable
{
    
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'confirmed' => 'boolean',
    ];
    
    public function getRouteKeyName()
    {
        return 'name';
    }
    
    public function threads()
    {
        return $this->hasMany(Thread::class)
                    ->latest();
    }
    
    public function lastReply()
    {
        return $this->hasOne(Reply::class)
                    ->latest();
    }
    
    
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
    
    
    public function read($thread)
    {
        // simulate the user visited the thread
        cache()->forever(
        //auth()->user()->visitedThreadCacheKey($thread), Carbon::now()
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }
    
    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }
    
    public function isAdmin()
    {
        return in_array($this->name, ['Jane','rainskiss']);
    }
    
    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }
    
    //public function avatar()
    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ? : 'images/avatars/default.png');
        //return $this->avatar_path ? : 'images/avatars/default.png';
        //if ( ! $this->avatar_path ){
        //    return "avatars/default.jpg";
        //}
        //
        //return $this->avatar_path;
    }
    
    
}
