<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function non_admin_may_not_lock_thread()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        // hit & endpoint
        //$this->patch($thread->path(), [
        //    'locked' => true,
        //])->assertStatus(403);
        $this->post(route('thread-lock.store', $thread), ['locked' => true])
             ->assertStatus(403);
        
        $this->assertFalse(! ! $thread->fresh()->locked);
        
    }
    
    /** @test */
    public function administrator_can_lock_threads()
    {
        // sign in as admin
        //$this->signIn(create('App\User',['name'=>'Jane']));
        $this->signIn(factory('App\User')->states('administrator')->create());
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        
        $this->post(route('thread-lock.store', $thread));
        //$this->patch($thread->path(), [
        //    'locked' => true,
        //]);
        
        $this->assertTrue($thread->fresh()->locked, 'Failed Asserting the thread is locked');
    }
    
    /** @test */
    public function administrator_can_unlock_thread()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());
        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => false]);
        $thread->lock();
        $this->delete(route('thread-lock.destroy', $thread));
        //$this->patch($thread->path(), [
        //    'locked' => true,
        //]);
        
        $this->assertFalse($thread->fresh()->locked, 'You can not unlock');
    }
    
    /** @test */
    public function once_locked_a_thread_may_not_receive_any_reply()
    {
        $this->signIn();
        $thread = create('App\Thread',['locked' => true]);
        //$thread->update(['locked'=>true]);
        $this->post($thread->path() . '/replies', [
            'body'    => 'foobar',
            'user_id' => auth()->id(),
        ])->assertStatus(422);
    }
}
