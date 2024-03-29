<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadLockController extends Controller
{
    
    public function store(Thread $thread)
    {
        if ( ! auth()->user()->isAdmin()){
            return response('', 403);
        }
        
        $thread->update(['locked'=>true]);
    }
    
    public function destroy(Thread $thread)
    {
        if ( ! auth()->user()->isAdmin()){
            return response('', 403);
        }
        $thread->update(['locked'=>false]);
    }
    
    
}
