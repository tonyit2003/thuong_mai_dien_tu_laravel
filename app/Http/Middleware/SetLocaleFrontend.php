<?php

namespace App\Http\Middleware;

use App\Models\Language;
use App\Repositories\CustomerRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFrontend
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customerId = Auth::guard('customers')->id();
        if (isset($customerId)) {
            $customer = $this->customerRepository->findById($customerId);
            $canonicalLanguage = $customer->language;
        } else {
            $canonicalLanguage = App::getLocale() ?? config('app.locale');
        }
        App::setLocale($canonicalLanguage);

        return $next($request);
    }
}
