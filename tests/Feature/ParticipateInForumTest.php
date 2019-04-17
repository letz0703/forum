<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ParticipateInForumTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function users_may_only_reply_a_maximun_of_once_per_minutes()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'My Simple Reply'
        ]);
        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertStatus(201);
        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertStatus(429);
    }
    
    /** @test */
    public function replies_that_contains_spam_maynot_be_created()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support',
        ]);
        
        //$this->expectException(\Exception::class);
        $this->json('post',$thread->path() . '/replies', $reply->toArray())
             ->assertStatus(422);
        
    }
    
    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        
        $this->post($thread->path() . '/replies', $reply->toArray());
    }
    
    
    /** @test */
    public function auth_user_may_participate_in_forum_threads()
    {
        //        $user = factory('App\User')->create();
        $this->be($user = factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        
        $this->post($thread->path() . '/replies', $reply->toArray());
        //        $this->get($thread->path())->assertSee($reply->body);
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }
    
    /** @test */
    //public function a_reply_requires_a_body()
    //{
    //    $this->withExceptionHandling()->signIn();
    //    $thread = factory('App\Thread')->create();
    //    $reply = make('App\Reply',['body'=>null]);
    //
    //    $this->post($thread->path() . '/replies', $reply->toArray())
    //         ->assertSessionHasErrors('body');
    //}
    //
    /** @test */
    public function unauth_user_cannot_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->delete("/replies/{$reply->id}")
             ->assertRedirect('login');
        $this->signIn();
        $this->delete("/replies/{$reply->id}")
             ->assertStatus(403);
    }
    
    /** @test */
    public function auth_user_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }
    
    /** @test */
    public function unauth_users_can_not_update_reply()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->patch("/replies/{$reply->id}")
             ->assertRedirect('login');
        
        $this->signIn();
        $this->patch("/replies/{$reply->id}")
             ->assertStatus(403);
    }
    
    
    /** @test */
    public function auth_user_can_update_reply()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $this->patch("/replies/{$reply->id}", ['body' => 'changed']);
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'changed']);
    }
    
    /** @test */
    public function auth_user_can_unfavorite_a_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');
        
        $reply->favorite(auth()->id());
        //        $this->post('/replies/'.$reply->id.'/favorites');
        //        $this->assertCount(1, $reply->favorites);
        
        $this->delete('/replies/' . $reply->id . '/favorites');
        $this->assertCount(0, $reply->favorites);
        //        $this->assertCount(0, $reply->fresh()->favorites);
    }
    
    
}
