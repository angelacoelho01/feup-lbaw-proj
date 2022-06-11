<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class FriendRequestController extends Controller
{
    public function reply(Request $request, $friend_request_id) 
    {
        $friend_request = FriendRequest::findOrFail($friend_request_id);
        if($request->input("type") == "accept") {
            $friend_request->accepted = true;
            $friend_request->save();
        } else {
            $friend_request->delete();
        }

        return redirect()->back();
    }

    public function add($user_id) 
    {
        $friend_request = new FriendRequest();
        $friend_request->sender_id = Auth::user()->id;
        $friend_request->receiver_id = $user_id;
        $friend_request->accepted = 'false';
        $friend_request->save();

        return redirect()->back();
    }

    public function remove($friend_request_id) 
    {
        $friend_request = FriendRequest::findOrFail($friend_request_id);
        $friend_request->delete();

        return redirect()->back();
    }

    public function show()
    {
        $friend_requests = FriendRequest::where('receiver_id',Auth::user()->id)->where('accepted', 'false')->get();
        return view('pages.friend-requests', [
            'friend_requests' => $friend_requests
        ]);
    }
}
