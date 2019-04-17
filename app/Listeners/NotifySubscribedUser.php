<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;

class NotifySubscribedUser
{
    
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply $event
     *
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $event->reply->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
