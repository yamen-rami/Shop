<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{App, Session};
use Closure;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get("locale") ?? "en" ;
        Session::put("locale" , $locale);
        App::setLocale($locale);
        return $next($request);
    }
}
