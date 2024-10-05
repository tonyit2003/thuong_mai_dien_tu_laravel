<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthClientController extends Controller
{
    public function __construct() {}

    public function index()
    {
        return view('frontend.auth.login');
    }

    public function login(AuthRequest $authRequest)
    {
        $credentials = [
            'email' => $authRequest->input('email'),
            'password' => $authRequest->input('password')
        ];

        // Thực hiện đăng nhập
        if (Auth::guard('customers')->attempt($credentials)) {
            flash()->success(__('toast.login_success'));
            return redirect()->route('home.index');
        }

        flash()->error(__('toast.login_failed'));
        return redirect()->route('authClient.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('customers')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home.index');
    }
}
