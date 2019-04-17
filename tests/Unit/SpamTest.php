<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SpamTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();
        $this->expectException('exception');
        $spam->detect('Hello World aaaaa');
    }
    
    
    /** @test */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('innocent reply here'));
        
        $this->expectException('exception');
        $spam->detect('Yahoo Customer Support');
    }
}
