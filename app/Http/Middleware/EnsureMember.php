<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMember
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isMember()) {
            abort(403, 'Akses ditolak. Halaman ini khusus member.');
        }
        return $next($request);
    }
}
