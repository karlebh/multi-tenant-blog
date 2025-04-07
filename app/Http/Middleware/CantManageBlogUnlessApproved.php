<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CantManageBlogUnlessApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() instanceof User && ! $request->user()->is_approved) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Your account is not yet approved.',
                ], 403);
            }

            abort(403, 'Unauthorized. Your account is not yet approved.');
        }

        return $next($request);
    }
}
