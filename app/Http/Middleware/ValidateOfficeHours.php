<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateOfficeHours
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \Illuminate\Auth\SessionGuard $auth */
        $my_user = auth()->user();

        // Check if the user is authenticated
        if($my_user == null) {
            return redirect('/')->with('error_msg', 'Login First');
        }

        // Check if the user has the required permissions
        if ($my_user->usertype != 1) {
            // Check if Current PH Time is within Office Hours (7am to 6pm)
            $current_time = now()->setTimezone('Asia/Manila')->format('H:i');
            if ($current_time < '07:00' || $current_time > '17:00') {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/')->with('error_msg', 'Access is available only from 7am to 6pm.');
            }
        }

        return $next($request);
    }
}
