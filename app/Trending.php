<?php


namespace App;


use Redis;

class Trending
{
    
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cache_key(), 0, 4));
    }
    
    
    public function push($thread)
    {
        Redis::zincrby($this->cache_key(), 1, json_encode([
            'title' => $thread->title,
            'path'  => $thread->path(),
        ]));
    }
    
    public function cache_key()
    {
        //return 'trending_threads';
        return app()->environment('testing')
            ? 'testing_trending_threads'
            : 'trending_threads';
    }
    
    public function reset()
    {
        Redis::del('testing_trending_threads');
    }
    
    
}