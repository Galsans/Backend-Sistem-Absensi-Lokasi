<?php

namespace App\Http\Controllers;

use App\Models\Kupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KuponController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $query = DB::table('kupons')
            ->leftJoin('users', 'users.id', '=', 'kupons.user_id')
            ->select(
                'kupons.*',
                'users.name as userName',
                'users.email as userEmail',
                'users.userCode',
            );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('kupons.jumlah', 'like', "%{$search}%");
            });
        }

        $kupon = $query
            ->orderBy('kupons.created_at', 'desc')
            ->orderBy('kupons.id', 'asc')
            ->cursorPaginate($request->limit ?? 10);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data kupon',
            'data' => $kupon
        ], 200);
    }

    public function show($id)
    {
        $kupon = Kupon::with('user')->find($id);

        if ($kupon == null) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }

        return response()->json([
            'statusCode' => 200,
            'msg' => 'detail kupon',
            'data' => $kupon
        ], 200);
    }

    public function search($search)
    {
        $kupon = DB::table('kupons')
            ->leftJoin('users', 'users.id', '=', 'kupons.user_id')
            ->select(
                'kupons.*',
                'users.name'
            )
            ->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%");
            })->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->cursorPaginate(10);

        if ($kupon->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $kupon
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'jumlah' => 'required|integer'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
                'data' => []
            ], 200);
        }

        $input = $validate->validate();

        $kupon = Kupon::create($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil disimpan',
            'data' => $kupon
        ], 200);
    }

    public function update(Request $request,  $id)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'jumlah' => 'required|integer'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $kupon = Kupon::find($id);
        $input = $validate->validated();

        $kupon->update($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil diubah',
            'data' => $kupon
        ], 200);
    }

    public function destroy($id)
    {
        $kupon = Kupon::find($id);
        $kupon->delete();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil dihapus',
            'data' => $kupon
        ], 200);
    }
}
