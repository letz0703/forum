<?php


namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    
    protected $thread;
    
    public function __construct($thread)
    {
        $this->thread = $thread;
    }
    
    public function reset()
    {
        Redis::del($this->CacheKey());
    
        return $this;
    }
    
    public function count()
    {
        return Redis::get($this->CacheKey()) ?? 0;
    }
    
    public function record()
    {
        Redis::incr($this->CacheKey());
    
        return $this;
    }
    
    
    public function CacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
    
}