<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
//        \DB::table('favorites')->insert([
//            'user_id' => auth()->id(),
//            'favorited_id' => $reply->id,
//            'favorited_type' => get_class($reply)
//        ]);
        $reply->favorite(auth()->id());
        return back();

    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
