<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController
{
    /**
     * Registrasi user baru dan langsung keluarkan token.
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // Hash::make: password disimpan dalam bentuk terenkripsi
            'password' => Hash::make($validated['password']),
            // role default 'user' untuk setiap registrasi
            'role' => 'user',
        ]);

        // createToken()->plainTextToken: buat personal access token Sanctum
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Berhasil Registrasi',
        ], 201);
    }

    /**
     * Login user dan keluarkan token baru.
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        // Cek user ada dan password cocok
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau Password Salah'],
            ]);
        }

        // Hapus token lama agar hanya ada satu sesi aktif
        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Berhasil Login',
        ]);
    }

    /**
     * Logout: hapus token yang sedang dipakai.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'data' => null,
            'message' => 'Berhasil Logout',
        ]);
    }
}
