<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    
    //public function __construct()
    //{
    //    $this->middleware('auth');
    //}
    
    /** Store New User Avatar
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store()
    {
        request()->validate([
            //$this->validate(request(), [
            'avatar' => ['required', 'image'],
        ]);
        
        auth()->user()->update([
            'avatar_path' => request()->file('avatar')
                                      ->store('avatars', 'public')
            //->storeAs('avatars', 'avatar.jpg', 'public'),
        ]);
        
        return response([], 204);
    }
    
}
