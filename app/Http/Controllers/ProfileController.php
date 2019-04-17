<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;

class ProfileController extends Controller {

    public function show(User $user)
    {
//        $activities = $user->activity()->with('subject')->latest()->get()
//                        ->groupBy('subject_type');

        //        return $activities;

        return view('profiles.show', [
            'profileUser' => $user,
//            'threads' => $user->threads()->paginate(10)
//            'activities' => $this->getactivity($user)
            'activities'  => Activity::feed($user)
//            'activities' => $user->activity()->paginate(20)
        ]);
    }


}
