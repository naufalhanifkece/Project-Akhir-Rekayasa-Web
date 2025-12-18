<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login dan generate API token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah',
                'success' => false
            ], 401);
        }

        // Generate token unik
        $token = Str::random(80);
        
        // Update user dengan token baru
        $user->update(['api_token' => $token]);

        return response()->json([
            'message' => 'Login berhasil',
            'success' => true,
            'data' => [
                'user' => $user,
                'api_token' => $token
            ]
        ], 200);
    }

    /**
     * Logout dan hapus token
     */
    public function logout(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan',
                'success' => false
            ], 401);
        }

        $user->update(['api_token' => null]);

        return response()->json([
            'message' => 'Logout berhasil',
            'success' => true
        ], 200);
    }

    /**
     * Get current user
     */
    public function me(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Token tidak valid atau user tidak ditemukan',
                'success' => false
            ], 401);
        }

        return response()->json([
            'message' => 'User berhasil diambil',
            'success' => true,
            'data' => $user
        ], 200);
    }
}
