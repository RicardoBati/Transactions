<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class MyUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    public function getMyUser()
    {
        return response()->json(Auth::guard('users')->user());
    }

}
