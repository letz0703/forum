<?php

namespace Tests\Feature;

use App\Rules\Recaptcha;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    
    use DatabaseMigrations;
    
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        
        app()->singleton(Recaptcha::class, function (){
            return \Mockery::mock(Recaptcha::class, function ($m){
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }
    
    /** @test */
    public function a_thread_requires_unique_slug()
    {
        $this->signIn();
        //$thread = create('App\Thread',[], 2);
        $thread = create('App\Thread', ['title' => 'Foo Title']);
        $this->assertEquals($thread->fresh()->slug, 'foo-title');
        //dd($thread->fresh()->slug);
        //$thread = $this->postJson(route('threads'),$thread->toArray())->json();
        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
        //$this->assertTrue(Thread::whereSlug('foo-title-4')->exists());
        //$thread = $this->post(route('threads'),$thread->toArray());
        //$this->assertEquals("foo-title-{$thread['id']}",$thread['slug']);
    }
    
    /** @test */
    public function a_therad_with_title_that_ends_in_a_number_should_generate_a_proper_slug()
    {
        $this->signIn();
        $thread = create('App\Thread', ['title' => 'Some Title 24']);
        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);
    }
    
    
    /** @test */
    public function new_user_must_confirm_their_email_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();
        $this->withExceptionHandling()->signIn($user);
        
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray())
             ->assertRedirect('/threads')
             ->assertSessionHas('flash', 'You must first confirm email');
    }
    
    /** @test */
    public function guests_cannot_create_thread()
    {
        //        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->withExceptionHandling();
        //        $thread = factory('App\Thread')->make();
        $this->get('/threads/create')
             ->assertRedirect(route('login'));
        $this->post('/threads')
             ->assertRedirect(route('login'));
        //        $thread = make('App\Thread');
        //        $this->post('/threads', $thread->toArray());
    }
    
    /** @test */
    public function an_auth_user_can_create_threads()
    {
        //        $this->actingAs(factory('App\User')->create());
        $this->signIn();
        $thread = make('App\Thread');
        //        $thread = factory('App\Thread')->create();
        $response = $this->publishThread(['title' => 'some title', 'body' => 'some body']);
        //$response = $this->post('/threads', $thread->toArray()+['g-recaptcha-response' => 'token']);
        
        //        dd($response->headers->get('Location'));
        $this->get($response->headers->get('Location'))
             ->assertSee('some title')
             ->assertSee('some body');
        //        $thread = factory('App\Thread')->raw();
        //        $this->post('$thread->path()',$thread);
        
    }
    
    /** @test */
    public function a_thread_require_a_title()
    {
        //$this->withExceptionHandling()->signIn();
        //        $thread = make('App\Thread',['title'=> null]);
        //        $this->post('/threads',$thread->toArray())
        //             ->assertSessionHasErrors('title');
        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }
    
    
    /** @test */
    public function a_thread_require_a_body()
    {
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }
    
    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();
        $this->publishThread(['channel_id' => null])
             ->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 999])
             ->assertSessionHasErrors('channel_id');
    }
    
    /** @test */
    public function a_thread_requires_g_recaptcha_verification()
    {
        unset(app()[ Recaptcha::class ]);
        $this->publishThread(['g-recaptcha-response' => 'test'])
             ->assertSessionHasErrors('g-recaptcha-response');
    }
    
   
    
    
    /** @test */
    public function unauthorized_user_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        
        $thread = create('App\Thread');
        //        $response = $this->json('DELETE',$thread->path());
        $this->delete($thread->path())->assertRedirect('/login');
        //        $this->delete($thread->path())->assertStatus(403);
        //
        $this->signIn();
        //        $this->delete($thread->path())->assertRedirect('/login');
        $this->delete($thread->path())->assertStatus(403);
        
    }
    
    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        
        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id'   => $thread->id,
            'subject_type' => get_class($thread),
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id'   => $reply->id,
            'subject_type' => get_class($reply),
        ]);
        //        $this->assertDatabaseMissing('threads',$thread->toArray());
    }
    
    
    /** @test */
    public function create_a_new_forum_thread()
    {
        $this->signIn();
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
        //dd($response->headers->get('Location'));
        //dd($thread->path());
        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title);
    }
    
    /** @test */
    
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
    
    /** @test */
    public function guest_can_not_see_create_thread_page()
    {
        $this->withExceptionHandling()
             ->get('/threads/create')
             ->assertRedirect('/login');
    }
    
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        
        //dd($thread);
        return $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
    }
    
    
}