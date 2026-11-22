<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Only authenticated users with the admin flag may enter the dashboard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $request->expectsJson()
                ? abort(401, __('ui.flash.unauthenticated'))
                : redirect()->route('admin.login');
        }

        if (! $request->user()->is_admin) {
            abort(403, __('ui.flash.adminForbidden'));
        }

        return $next($request);
    }
}
