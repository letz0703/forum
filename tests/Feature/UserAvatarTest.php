<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserAvatarTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function a_user_may_add_avatar_to_their_profile()
    {
        $this->signIn();
        
        Storage::fake('public');
        
        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg'),
        ]);
        
        //dd(auth()->user()->avatar_path);
        $this->assertEquals(asset('avatars/'.$file->hashName()), auth()->user()->avatar_path);
        
        //$this->assertEquals(asset('avatars/' . $file->hashName()),
        //    auth()->user()->avatar_path);
        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
        
    }
    
    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()->signIn();
        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => 'not-an-image',
        ])->assertStatus(422);
    }
    
    /** @test */
    public function only_members_can_create_avatars()
    {
        $this->withExceptionHandling();
        $this->json('POST', '/api/users/1/avatar')
             ->assertStatus(401);
    }
    
    
}
