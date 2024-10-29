<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends FrontendController
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        parent::__construct();
        $this->customerService = $customerService;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $this->customerService->createFromGoogle($googleUser);
        $credentials = [
            'email' => $googleUser->getEmail(),
            'password' => $googleUser->getId()
        ];
        if (Auth::guard('customers')->attempt($credentials)) {
            flash()->success(__('toast.login_success'));
            return redirect()->route('home.index');
        }
        flash()->error(__('toast.login_failed'));
        return redirect()->route('authClient.index');
    }
}
