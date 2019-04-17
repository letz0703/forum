<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->signIn();
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);
        self::assertCount( 1, auth()->user()->unreadNotifications );

        self::assertCount( 1, $this->getJson( "/profiles/" . auth()->user()->name . "/notifications/" )->json() );
    }


    /** @test */
    public function a_user_can_mark_a_notifiaction_as_read()
    {
        create(DatabaseNotification::class);

        tap(auth()->user(),function($user){
            self::assertCount( 1, auth()->user()->unreadNotifications );
            $notificationId = $user->unreadNotifications->first()->id;
            $endpoint = "/profiles/" . $user->name . "/notifications/" . $notificationId;
            $this->delete( $endpoint );

            self::assertCount( 0, $user->fresh()->unreadNotifications );
        });
    }

    /** @test */
    public function notify_subscribed_users_who_is_not_reply_owner()
    {
        $thread = create( 'App\Thread' )->subscribe();

        $this->assertCount( 0, auth()->user()->notifications );

        $thread->addReply( [
                               'user_id' => auth()->id(),
                               'body'    => 'temporary',
                           ] );
        $this->assertCount( 0, auth()->user()->fresh()->notifications );

        $thread->addReply( [
                               'user_id' => create( 'App\User' )->id,
                               'body'    => 'temporary',
                           ] );
        $this->assertCount( 1, auth()->user()->fresh()->notifications );
    }

}
