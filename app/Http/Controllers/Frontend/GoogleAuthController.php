<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends FrontendController
{
    protected $customerService;
    protected $customerRepository;

    public function __construct(CustomerService $customerService, CustomerRepository $customerRepository)
    {
        parent::__construct();
        $this->customerService = $customerService;
        $this->customerRepository = $customerRepository;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        if ($this->customerRepository->checkEmailExists($googleUser->getEmail())) {
            flash()->error(__('toast.email_registered'));
            return redirect()->route('authClient.index');
        }
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
