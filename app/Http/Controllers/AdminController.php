<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function show($user)
    {
        $user = User::findorFail($user);

        if (!$user->is_admin) {
            return redirect('/home');
        }

        $bans = Ban::where('user_id', $user->id)->get();

        return view('pages.admin.dashboard', ['bans' => $bans]);
    }

    public function update(Request $request)
    {
        $id = (int) $request->input('id');
        $user = User::find($id);

        if ($request->input('ban-motive') !== null) {
            $ban = new Ban;
            $ban->user_id = Auth::user()->id;
            $ban->banned_id = $id;
            $ban->motive = $request->input('ban-motive');
            $ban->date_time = date('Y-m-d H:i:s');
            $ban->save();
        } else if ($request->input('delete')) {
            $user->delete();
        } else if ($request->input('submit') === 'lock_open') {
            $ban = Ban::where('banned_id', $id)->get()->first();
            $ban->delete();
        } else if ($request->input('submit') === 'delete') {
            $user->delete();
        }

        return redirect()->back();
    }
}
