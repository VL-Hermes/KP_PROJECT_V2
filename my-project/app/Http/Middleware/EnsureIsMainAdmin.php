<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsMainAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isMainAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Main Admin privilege required.'], 403);
            }

            return redirect()->route('laptops.index')
                ->with('error', 'Akses ditolak: Hanya Main Admin yang memiliki izin untuk mengelola daftar admin.');
        }

        return $next($request);
    }
}

