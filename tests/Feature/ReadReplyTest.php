<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_request_all_replies_from_a_given_thread()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply',['thread_id'=>$thread->id],11);
        $response = $this->getJson($thread->path().'/replies')->json();

        //dd($response);
        $this->assertCount(10, $response['data']);
        $this->assertEquals(11, $response['total']);
    }
}
