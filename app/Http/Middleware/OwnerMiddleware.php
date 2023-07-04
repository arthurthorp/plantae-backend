<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if(!$user) {
            return response()->json([
                'message' => 'Acesso negado',
            ], 401);
        }

        if(!$user->is_owner) {
            return response()->json([
                'message' => 'Você não tem permissão para executar essa ação',
            ], 401);
        }

        return $next($request);
    }
}
