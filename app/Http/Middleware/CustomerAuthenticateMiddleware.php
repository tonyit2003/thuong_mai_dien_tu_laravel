<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('customers')->check()) {
            // kiểm tra xem yêu cầu hiện tại có phải là một yêu cầu AJAX hay không
            if ($request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => __('toast.login_required'),
                    'redirect' => route('authClient.index')
                ], 401);
            }
            flash()->error(__('toast.login_required'));
            return redirect()->route('authClient.index');
        }

        return $next($request);
    }
}
