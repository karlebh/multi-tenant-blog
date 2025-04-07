<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdminCanManageAllBlogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() instanceof User && auth()->id() !== $request->tenant->id) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Only admins can access this resource.',
                ], 403);
            }

            return redirect()->route('blogs.index', $request->user());
        }

        return $next($request);
    }
}
