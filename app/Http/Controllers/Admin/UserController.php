<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Profile()
    {
        return view('site.profile.profile');
    }

    public function profileUpdate(Request $request)
    {
        dd($request->all());
    }
}