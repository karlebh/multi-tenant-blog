<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class YouCantInteractWithOthersBlog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->id() !== $request->user_id) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Only admins can access this resource.',
                ], 403);
            }

            return redirect()->back();
        }

        return $next($request);
    }
}
