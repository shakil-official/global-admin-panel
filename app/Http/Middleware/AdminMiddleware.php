<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated as admin
        if (!Auth::guard('admin')->check()) {
            return redirect('/admin/login'); // Redirect to the login page or any other page
        }

        return $next($request);
    }
}
