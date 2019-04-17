<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;
use App\Filters\ThreadFilters;
use Laravel\Scout\Searchable;
use Stevebauman\Purify\Purify;

/**
 * Class Thread
 * @package App
 */
class Thread extends Model
{
    
    use RecordActivity, Searchable;
    
    protected $guarded = [];
    
    protected $with = ['creator', 'channel'];
    
    protected $appends = ['isSubscribedTo'];
    
    protected $casts = [
        'locked' => 'boolean'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        //        static::addGlobalScope('replyCount', function ($builder)
        //        {
        //            $builder->withCount('replies');
        //        });
        
        //        static::addGlobalScope('creator',function($builder){
        //            $builder->with('creator');
        //        });
        static::deleting(function ($thread){
            //            $thread->replies()->delete();
            $thread->replies->each->delete();
            //            $thread->replies->each(function($reply){
            //                $reply->delete();
            //            });
        });
        
        static::created(function ($thread){
            $thread->update(['slug' => $thread->title]);
        });
    }
    
    /**
     * @return string
     */
    public function path()
    {
        //        return "/threads/".$this->channel->slug.'/'.$this->id;
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class);
        //            ->withCount('favorites')
        //            ->with('owner');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        
        event(new ThreadReceivedNewReply($reply));
        
        //$this->notifySubscriber($reply);// listener
        
        return $reply;
    }
    
    public function notifySubscriber($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
    
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }
    
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }
    
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create(['user_id' => $userId ? : auth()->id()]);
        
        return $this;
    }
    
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ? : auth()->id())->delete();
    }
    
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }
    
    public function hasUpdatesFor($user = null)
    {
        $user = $user ? : auth()->user();
        //$key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);
        $key = $user->visitedThreadCacheKey($this);
        
        return $this->updated_at > cache($key);
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);
        //$original = $slug;
        //$count = 2;
        if (static::whereSlug($slug)->exists()){
            $slug = "{$slug}-" . $this->id;
        }
        
        //while (static::whereSlug($slug)->exists()){
        //    $slug = "{$original}-" . $count ++;
        //}
        
        $this->attributes['slug'] = $slug;
        
        //return $slug;
        //if (Thread::whereSlug($slug = str_slug($value))->exists()){
        //    $slug = $this->incrementSlug($slug);
        //}
        //
        //return $this->attributes['slug'] = $slug;
        //dd($this->slug);
    }
    
    /**
     * @param     $slug
     * @param int $count
     *
     * @return string
     */
    //public function incrementSlug($slug, $count = 2)
    //{
    //    $original = $slug;
    //
    //    while (static::whereSlug($slug)->exists()){
    //        $slug = "{$original}-" . $count ++;
    //    }
    
    //return $slug;
    //$max = static::whereTitle($this->title)->max('slug');
    //$max = static::whereTitle($this->title)->latest('id')->value('slug');
    //
    //if (is_numeric($max[-1])){
    //    return preg_replace_callback('/(\d+)$/', function ($matches){
    //        return $matches[1]+1;
    //    }, $max);
    //}
    //return "{$slug}-2";
    //}
    
    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
        //$this->best_reply_id = $reply->id;
        //$this->save();
    }
    
    public function toSearchableArray()
    {
        return $this->toArray()+['path'=>$this->path()];
        //return ['title' => $this->title];
    }
    
    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }
    
    
}

