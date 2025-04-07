<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdminAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! ($request->user() instanceof  Admin)) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Only admins can access this resource.',
                ], 403);
            }

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
