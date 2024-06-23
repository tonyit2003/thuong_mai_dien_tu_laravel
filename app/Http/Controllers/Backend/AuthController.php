<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('backend.auth.login');
    }

    public function login(AuthRequest $authRequest)
    {
        $credentials = [
            'email' => $authRequest->input('email'),
            'password' => $authRequest->input('password')
        ];

        // Kiểm tra đăng nhập thành công
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard.index');
        }

        flash()->error('Email hoặc mật khẩu không chính xác');
        return redirect()->route('auth.admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin');
    }
}
