<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_user_can_determin_their_avatar_path()
    {
        //$user = create('App\User',['avatar_path'=>"avatars/me.jpg"]);
        $user = create('App\User');
        $this->assertEquals(asset("images/avatars/default.png"), $user->avatar_path);
        $user->avatar_path = "avatars/me.jpg";
        $this->assertEquals(asset("avatars/me.jpg"), $user->avatar_path);
    }
    
    
    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('App\User');
        $reply = create('App\Reply',['user_id'=>$user->id]);
        $this->assertEquals($reply->id, $user->lastReply->id);
    }
}
