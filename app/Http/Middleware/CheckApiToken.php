<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil token dari header Authorization: Bearer TOKEN
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu',
                'success' => false
            ], 401);
        }

        // Cari user dengan token yang sesuai
        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token tidak valid atau sudah expired',
                'success' => false
            ], 401);
        }

        // Set user ke request untuk bisa diakses di controller
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
