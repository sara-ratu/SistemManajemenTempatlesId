<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMurid
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isMurid()) {
            abort(403, 'Akses ditolak. Halaman ini khusus murid.');
        }
        return $next($request);
    }
}
