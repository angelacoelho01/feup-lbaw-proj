<?php

namespace App\Http\Controllers\Static;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    public function show()
    {
        return view('static.contact-us');
    }
}
