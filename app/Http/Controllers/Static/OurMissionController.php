<?php

namespace App\Http\Controllers\Static;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OurMissionController extends Controller
{
    public function show()
    {
        return view('static.our-mission');
    }
}
