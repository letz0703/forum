<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Reply $reply)
    {
        return $user->id == $reply->user_id;
    }
    
    public function create(User $user)
    {
        $lastReply = $user->fresh()->lastReply;
        if (! $lastReply){
            return true;
        }
        return ! $lastReply->wasJustPublished();
    }
}
