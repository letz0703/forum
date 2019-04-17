<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_can_be_filtered_by_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread',['channel_id'=>$channel->id]);
        $threadNotInChannel = create('App\Thread');
        $this->get('/threads/'.$channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_channel_consists_of_threads()
    {
        $channel = create('App\Channel');
        $thread = create('App\Thread',['channel_id'=>$channel->id]);
        $this->assertTrue($channel->threads->contains($thread));
    }

}


