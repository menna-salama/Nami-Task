<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (! $token = Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('login.form')
                ->withErrors(['email' => 'please check your email and password'])
                ->withInput();
        }

        session(['admin_jwt' => $token]);
        return redirect()->route('report.index');
    }
}


