<?php

namespace App\Http\Middleware;

use App\Enums\User\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user is an admin
        if (Auth::check() && Auth::user()->role == Role::ADMIN) {
            return $next($request);
        }

        // Redirect if the user is not an admin
        return redirect()->back()->with('error', 'You are not authorized to perform delete action page.');
    }
}
