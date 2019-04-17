<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreatorTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function a_thread_has_a_creator()
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('App\User',$thread->creator);
    }
}
