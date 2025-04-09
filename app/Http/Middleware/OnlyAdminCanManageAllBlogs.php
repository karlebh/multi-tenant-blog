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
        $tenantId = $request->tenant ? $request->tenant->id : $request->tenant_id;

        if (auth()->user() instanceof User && auth()->id() !== (int) $tenantId) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Only admins can manage all blogs.',
                    'tenant_it' => $request->tenant_id,
                    'user' => $request->user()->load('blog'),
                    'user_id' => auth()->id(),
                ], 403);
            }

            return redirect()->route('blogs.index', $request->user());
        }

        return $next($request);
    }
}
