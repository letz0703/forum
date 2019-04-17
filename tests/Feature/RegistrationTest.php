<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm'), ['token' => 'invalid'])
             ->assertRedirect(route('threads'))
             ->assertSessionHas('flash', 'Unknown Token');
    }
    
    /** @test */
    public function a_confirmation_email_sent_upon_registration()
    {
        Mail::fake();
        //event(new Registered(create('App\User')));
        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'John@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);
        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }
    
    /** @test */
    public function a_user_can_fully_confirm_their_email_addresses()
    {
        Mail::fake();
        
        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'John@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);
        
        $user = User::whereName('John')->first();
        
        $this->assertFalse($user->confirmed);
        
        $this->assertNotNull($user->confirmation_token);
        
        // Let the user confirm their account
        //$response = $this->get('/register/confirm?token=' . $user->confirmation_token);
        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
             ->assertRedirect(route('threads'));
        
        tap($user->fresh(), function($user){
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
        

    }
    
}
