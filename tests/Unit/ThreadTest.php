<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification;
use Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    
    use DatabaseMigrations;
    
    protected $thread;
    
    function setUp()
    {
        parent::setup();
        $this->thread = factory('App\Thread')->create();
    }
    
    ///** @test */
    //public function it_records_each_visit()
    //{
    //    $thread = make('App\Thread', ['id' => 1]);
    //    $thread->visits()->reset();
    //    //$thread->resetVisits();
    //    $this->assertSame(0, $thread->visits()->count());
    //    $thread->visits()->record();
    //    $this->assertEquals(1, $thread->visits()->count());
    //    //$thread->recordVisit();
    //    //$this->assertEquals(2, $thread->visits());
    //}
    
    /** @test */
    public function a_thread_has_a_path()
    {
        $thread = create('App\Thread');
        //        $this->assertEquals('/threads/'.$thread->channel->slug.'/'.$thread->id, $thread->path());
        $this->assertEquals("/threads/{$thread->channel->slug}/$thread->slug", $thread->path());
    }
    
    /** @test */
    public function a_thread_can_check_if_auth_user_has_read_all_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        
        
        tap(auth()->user(), function ($user) use ($thread){
            $this->assertTrue($thread->hasUpdatesFor($user));
            
            $user->read($thread);
            
            self::assertFalse($thread->hasUpdatesFor($user));
        });
        
        
    }
    
    
    /** @test */
    public function a_thread_can_notify_subscribed_users_when_a_reply_left()
    {
        Notification::fake();
        
        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body'    => 'foobar',
                'user_id' => factory('App\User')->create()->id,
            ]);
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }
    
    
    /** @test */
    public function thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);
        $response = $thread->subscriptions()->where('user_id', $userId)->count();
        $this->assertEquals(1, $response);
    }
    
    /** @test */
    public function thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);
        $thread->unsubscribe($userId);
        $this->assertCount(0, $thread->subscriptions);
        //        $response = $thread->subscription()->where('user_id',$userId)->count();
        //        $this->assertEquals(0, $response);
    }
    
    /** @test */
    function a_thread_can_add_reply()
    {
        $this->thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1,
        ]);
        $this->assertCount(1, $this->thread->replies);
    }
    
    /** @test */
    function a_thread_may_be_locked()
    {
        $this->assertFalse($this->thread->locked);
        $this->thread->update(['locked' => true]);
        $this->assertTrue($this->thread->locked);
    }
    
    /** @test */
    public function a_thread_body_is_sanitized_automatically()
    {
        $thread = make('App\Thread',['body'=>'<script>alert("bad")</script><p>This is Ok</p>']);
        //dd($thread->body);
        //$this->assertEmpty($thread->body);
        $this->assertEquals("<p>This is Ok</p>", $thread->body);
    }
    
    
}
