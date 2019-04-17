<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase {

    /** @test */
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }
    
    /** @test */
    public function we_records_a_visit_each_time_a_thread_is_read()
    {
        $thread = create('App\Thread');
        //dd($thread->fresh()->toArray());
        $this->assertSame(0, $thread->visits);
        $this->call('GET',$thread->path());
        $this->assertEquals(1, $thread->fresh()->visits);
    }
    


    /** @test */
    public function a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->post($thread->path() . '/subscriptions');
//        $this->assertCount(1,$thread->subscription);
        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'some text'
        ]);
        $this->assertTrue($thread->isSubscribedTo);
//        $this->assertCount(1,
//            auth()->user()->notifications
//        );
    }


    /** @test */
    public function users_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $response = $this->getJson('threads?unanswered=1')->json();
        $this->assertCount(1, $response['data']);
    }

    /** @test */
    public function a_user_can_browse_all_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_browse_a_single_thread()
    {
        $this->get('/threads/' . $this->thread->channel . '/' . $this->thread->slug)
//        $this->get('/threads/'. $this->thread->id)
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_reply()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $this->get('/threads/' . $this->thread->channel . '/' . $this->thread->slug);
//            ->assertSee($reply->body);
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }

    /** @test */
    public function a_user_can_filter_threads_by_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));
        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithThreeReplies = create('App\Thread');
        $threadWithTwoReplies = create('App\Thread');
        $threadWithNoReply = $this->thread;

        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $response = $this->getJson('threads?popular=1')->json();

        //        $response->assertSee($threadWithThreeReplies->title);
        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }

}
