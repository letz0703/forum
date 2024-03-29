<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function it_wraps_mentioned_users_name_in_the_body_within_anchor_tags()
    {
        //$reply = create('App\Reply', [
        $reply = new \App\Reply([
            'body' => 'Hello @Jane-Doe.',
        ]);
        
        $this->assertEquals('Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.', $reply->body);
    }
    
    
    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();
        $this->assertInstanceOf('App\User', $reply->owner);
    }
    
    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');
        $this->assertTrue($reply->wasJustPublished());
        
        $reply->created_at = Carbon::now()->subMonth();
        $this->assertFalse($reply->wasJustPublished());
    }
    
    /** @test */
    public function it_can_detect_all_mentioned_user_in_a_reply()
    {
        //$reply = create('App\Reply', [
        $reply = new \App\Reply([
            'body' => '@JaneDoe wants to talk to @JohnDoe',
        ]);
        
        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers($reply->body));
    }
    
    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');
        $this->assertFalse($reply->isBest());
        $reply->thread->update(['best_reply_id' => $reply->id]);
        $this->assertTrue($reply->fresh()->isBest());
    }
    
    
}
