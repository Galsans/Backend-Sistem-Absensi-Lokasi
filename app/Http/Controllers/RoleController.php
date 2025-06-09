<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // private function decryptSecureToken($token, $expectedRoute)
    // {
    //     try {
    //         $token = str_replace(['-', '_'], ['+', '/'], $token);
    //         $padded = str_pad($token, strlen($token) % 4 === 0 ? strlen($token) : strlen($token) + (4 - strlen($token) % 4), '=', STR_PAD_RIGHT);
    //         $decoded = base64_decode($padded);

    //         $iv = substr($decoded, 0, 16);
    //         $ciphertext = substr($decoded, 16);

    //         $decrypted = openssl_decrypt($ciphertext, 'AES-128-CBC', 'my-secret-key-1234567890123456', OPENSSL_RAW_DATA, $iv);
    //         $payload = json_decode($decrypted, true);

    //         if (!isset($payload['id'], $payload['route'], $payload['timestamp'], $payload['nonce'])) {
    //             return null;
    //         }

    //         // Check if route matches
    //         if ($payload['route'] !== $expectedRoute) {
    //             return null;
    //         }

    //         // Optional: Check expiry (e.g., 2 minutes)
    //         if ((now()->timestamp * 1000) - $payload['timestamp'] > 120000) {
    //             return null;
    //         }

    //         // One-time use: check if nonce has been used
    //         $cacheKey = 'used-token:' . $payload['nonce'];
    //         if (Cache::has($cacheKey)) {
    //             return null;
    //         }

    //         Cache::put($cacheKey, true, now()->addMinutes(2)); // valid for 2 minutes

    //         return $payload['id']; // Return ID if valid
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }


    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $role = $query->orderBy('id')->cursorPaginate($request->limit ?? 10);


        return response()->json([
            'statusCode' => 200,
            'msg' => 'data role',
            'data' => $role
        ], 200);
    }

    public function show($id)
    {
        // $id = decryptSecureToken($token, 'role-detail');

        // if (!$id) {
        //     abort(404);
        // }

        $role = Role::find($id);
        if ($role == null) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'detail role',
            'data' => $role
        ], 200);
    }

    public function search($search)
    {
        $role = Role::where(function ($q) use ($search) {
            $q->where('name', 'lile', "%{$search}%");
        })->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->cursorPaginate(10);

        if ($role->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $role
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
                'data' => []
            ], 200);
        }

        $input = $validate->validate();

        $role = Role::create($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil disimpan',
            'data' => $role
        ], 200);
    }

    public function update(Request $request,  $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $role = Role::find($id);
        $input = $validate->validated();

        $role->update($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil diubah',
            'data' => $role
        ], 200);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil dihapus',
            'data' => $role
        ], 200);
    }
}
