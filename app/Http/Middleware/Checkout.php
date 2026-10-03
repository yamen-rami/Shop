<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class Checkout
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return redirect()->route("login");
        }
        $cart = app(\App\Services\StorefrontData::class)->cart();
        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route("home");
        }
        return $next($request);
    }
}
