<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // lấy giá trị của app_locale từ session (nếu không có trong session thì sẽ lấy trong app.php)
        $locale = session('app_locale', config('app_locale'));
        // thiết lập ngôn ngữ cho cho route đang gọi Middleware SetLocale (khi chuuển hướng, gía trị locale sẽ được reset)
        App::setLocale($locale);

        return $next($request);
    }
}
