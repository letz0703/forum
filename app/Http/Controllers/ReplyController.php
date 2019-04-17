<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }
    
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->latest()->paginate(10);
    }
    
    /**
     * @param                                      $channelId
     * @param Thread                               $thread
     *
     * @param \App\Http\Requests\CreatePostRequest $form
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if ( $thread->locked ){
            return response('This thread is locked',422);
        }
        
        return $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner');
    }
    
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $this->validate(request(), ['body' => 'required|spamfree']);
        request()->validate(['body' => 'required|spamfree']);
        
        $reply->update(['body' => request('body')]);
    }
    
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();
        if (request()->expectsJson()){
            return response(['status' => '삭제완료']);
        }
        
        return back();
    }
    
}
