<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTutor
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isTutor()) {
            abort(403, 'Akses ditolak. Halaman ini khusus tutor.');
        }
        return $next($request);
    }
}
