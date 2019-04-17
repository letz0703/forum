<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadSubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_knows_when_user_subscribed_to_a_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path().'/subscriptions');
        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function user_can_subscribe_to_a_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->post($thread->path().'/subscriptions');
        $this->assertCount(1, $thread->subscriptions);

//        $this->assertCount(0, auth()->user()->notifications);
//
//        $thread->addReply([
//            'user_id' => auth()->id(),
//            'body' => 'temporary'
//        ]);
//        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }


    /** @test */
    public function user_can_unsubscribe_from_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->delete($thread->path().'/subscriptions');
        $this->assertFalse($thread->isSubscribedTo);
    }

}
