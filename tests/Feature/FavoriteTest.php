<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoriteTest extends TestCase {

    use DatabaseMigrations;

    /** @test */
    public function guest_may_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }


    /** @test */
    public function auth_user_can_favorite_threads()
    {
        $this->signIn();
//        $thread = create('App\Thread');
//        $reply = create('App\Reply',['thread_id'=>$thread->id]);
        $reply = create('App\Reply');
        $this->post('replies/'.$reply->id.'/favorites');
//        dd(\App\Favorite::all());
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function auth_user_can_only_reply_once()
    {
        try
        {
            $this->signIn();
//        $thread = create('App\Thread');
//        $reply = create('App\Reply',['thread_id'=>$thread->id]);
            $reply = create('App\Reply');
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
//        dd(\App\Favorite::all()->toArray());
            $this->assertCount(1, $reply->favorites);
        } catch(\Exception $e) {
            $this->fail('You can not favorite twice');
        }
    }

}
