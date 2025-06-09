<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Dapatkan user dan tambahkan role ke dalam claims token
            $user = Auth::user();
            $token = JWTAuth::fromUser($user); // Menggunakan method dari user dengan

        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return $this->respondWithToken($token);
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'EmpCode'  => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $user = User::where('EmpCode', $request->EmpCode)->first();

    //     if (!$user || !password_verify($request->password, $user->password)) {
    //         // atau gunakan Laravel-style: !Hash::check($request->password, $user->password)
    //         return response()->json(['error' => 'Invalid credentials'], 401);
    //     }

    //     // Buat token JWT jika password benar
    //     $token = JWTAuth::fromUser($user);

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type'   => 'bearer',
    //         'expires_in'   => auth('api')->factory()->getTTL() * 60,
    //     ]);
    // }



    // Method to respond with token details
    protected function respondWithToken($token)
    {
        return response()->json([
            'statusCode' => 200,
            'user' => JWTAuth::user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL(), // Mengembalikan waktu dalam detik
            'refresh_in' => JWTAuth::factory()->getTTL(), // Mengembalikan waktu dalam detik
        ], 200);

        if (JWTAuth::parseToken()->isExpired()) {
            // Refresh token jika sudah expired
            JWTAuth::refresh();
        }
    }

    public function profileMe()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'statusCode' => 404,
                    'error' => 'User not found'
                ], 404);
            }

            // Ambil payload dari token untuk mendapatkan role
            $payload = JWTAuth::parseToken()->getPayload();
            $role = $payload->get('role'); // Akses role dari payload

            return response()->json([
                'statusCode' => 200,
                'user' => $user,
                'role' => $role,
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token invalid or expired'], 401);
        }
    }


    // public function logout(Request $request)
    // {
    //     try {
    //         // Ambil token dari header Authorization
    //         $token = $request->bearerToken();

    //         if (!$token) {
    //             return response()->json([
    //                 'statusCode' => 401,
    //                 'message' => 'Token not provided'
    //             ], 401);
    //         }

    //         // Blacklist token
    //         JWTAuth::setToken($token)->invalidate();

    //         return response()->json([
    //             'statusCode' => 200,
    //             'message' => 'Successfully logged out'
    //         ], 200);
    //     } catch (JWTException $e) {
    //         return response()->json([
    //             'statusCode' => 500,
    //             'message' => 'Failed to logout, please try again'
    //         ], 500);
    //     }
    // }
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout berhasil.'
        ]);
    }
}
