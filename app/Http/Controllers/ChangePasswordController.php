<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.change-password');
    }

    public function store(Request $request)
    {

        $request->validate([
            'password_old' => ['required', new MatchOldPassword],
            'password' => ['required'],
            'password_confirmation' => ['same:password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->input('password'))]);

        return redirect('/home');
    }
}
