<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Rules\Recaptcha;
use App\Thread;
use App\Filters\ThreadFilters;
use App\Trending;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Zttp\Zttp;

class ThreadController extends Controller
{
    
    /**
     * ThreadController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param Channel       $channel
     * @param ThreadFilters $filters
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        
        $threads = $this->getThreads($channel, $filters);
        
        if (request()->wantsJson()){
            return $threads;
        }
        
        $trending->get();
        
        //$trending = collect(Redis::zrevrange('trending_threads', 0, 4))->map(function ($thread){
        //    return json_decode($thread);
        //});
        
        return view('threads.index', [
            'threads'  => $threads,
            'trending' => $trending->get(),
        ]);
        
        //compact('threads', 'trending'));
    }
    //    public function index($channelSlug = null)
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //        if (!auth()->login()) {
        //            return redirect('/login');
        //        }
        return view('threads.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Recaptcha $recaptcha
     *
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     *
     */
    public function store(Recaptcha $recaptcha)
    {
        //if ( ! auth()->user()->confirmed){
        //    return redirect('/threads')->with('flash','You must first confirm email');
        //}
        
        //request()->validate([
        //$this->validate($request,[
        request()->validate([
            'title'                => 'required|spamfree',
            'body'                 => 'required|spamfree',
            'channel_id'           => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha],
        ]);
        
        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => request('channel_id'),
            'title'      => request('title'),
            'body'       => request('body'),
        ]);
        
        if (request()->wantsJson()){
            return response($thread, 201);
        }
        
        return redirect($thread->path())
            ->with('flash', 'Thread has been published');
    }
    
    /**
     * Display the specified resource.
     *
     * @param              $channelId
     * @param  \App\Thread $thread
     *
     * @param Trending     $trending
     *
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        //        return $thread->append('isSubscribedTo');
        //        return $thread->load('replies');
        //        return Thread::withCount('replies')->first();
        //        return Thread::withCount('replies')->find(55);
        //        return $thread->replies;
        
        if (auth()->check()){
            auth()->user()->read($thread);
        }
        
        $trending->push($thread);
        $thread->increment('visits');
        
        //$thread->visits()->record();
        
        return view('threads.show', compact('thread'));
        
        //        return view('threads.show', [
        //            'thread'  => $thread,
        //            'replies' => $thread->replies()->paginate(20)
        //        ]);
        //        return view('threads.show', compact('thread'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  Request     $request
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    
    public function update($chanel, Thread $thread)
    {
        $this->authorize('update', $thread);
        // validation
        $thread->update(request()->validate([
            'title' => 'required|spamfree',
            'body'  => 'required|spamfree',
        ]));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        
        $thread->delete();
        
        if (request()->wantsJson()){
            return response([], 204);
        }
        
        return redirect('/threads');
    }
    
    /**
     * @param Channel       $channel
     * @param ThreadFilters $filters
     *
     * @return mix
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        
        if ($channel->exists){
            $threads->where('channel_id', $channel->id);
        }
        
        return $threads->paginate(25);
        
        //        $threads = $this->getThreads($channel);
    }
}
