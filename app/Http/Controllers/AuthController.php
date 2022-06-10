<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('Auth.login');
    }
    public function authLogin(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($user = Admin::where('email', $request->email)->first()) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('auth', [
                    'check' => true,
                    'data' => $user
                ]);
                return redirect()->intended('mynotes-users');
            }
        }

        return back()->with('failed', 'Email or password wrong');
    }

    public function authLogout()
    {
        Session::forget('auth');
        return redirect('/auth/login');
    }
}
