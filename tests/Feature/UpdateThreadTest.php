<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
        parent::setUp();
    
        $this->withExceptionHandling();
        $this->signIn();
    }
    
    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->patch($thread->path(),[
            'title' =>'changed',
        ])->assertSessionHasErrors('body');
        
        $this->patch($thread->path(),[
            'body' =>'changed body',
        ])->assertSessionHasErrors('title');
    }
    
    /** @test */
    public function unauth_user_may_not_update_threads()
    {
        $thread = create('App\Thread',['user_id'=>create('App\User')->id]);
        $this->patch($thread->path(),[])->assertStatus(403);
    }
    
    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->patchJson($thread->path(), [
            'title' => 'changed',
            'body'  => 'changed body',
        ]);
        
        tap($thread->fresh(), function($thread){
            $this->assertEquals('changed', $thread->title);
            $this->assertEquals('changed body', $thread->body);
        });
        
    }
}
