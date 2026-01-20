<?php

namespace App\Http\Middleware;

use App\Facades\ApiResponse;
use App\Constants\HttpStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return ApiResponse::unauthorized();
        }

        if (!$request->user()->role || !in_array($request->user()->role->key, $roles)) {
            return ApiResponse::error(HttpStatus::FORBIDDEN, null, 'Forbidden: Insufficient permissions');
        }

        return $next($request);
    }
}
