<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Null_;

class RegisterConfirmationController extends Controller
{
    
    public function index()
    {
        $user = User::where('confirmation_token', request('token'))
                    ->first();
        if ( ! $user){
            return redirect(route('threads'))->with('flash', 'Unknown Token');
        }
        $user->confirm();
        
        return redirect('/threads')
            ->with('flash', 'Your account is now confirmed. You may post to this forum');
        
    }
    
}
