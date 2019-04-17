<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SearchTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);
        $search = 'foobar';
        create('App\Thread', [], 2);
        create('App\Thread', ['title' => "A thread with {$search} term"], 2);
        do {
            sleep(.25);
            $results = $this->getJson("/threads/search?q={$search}")->json();
        } while(empty($results));
    
        //$results = $this->getJson("/threads/search?q={$search}")->json();
        $this->assertCount(2, $results['data']);
        
        Thread::latest()->take(4)->unsearchable();
    }
}
