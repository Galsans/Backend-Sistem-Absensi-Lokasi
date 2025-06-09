<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $query = DB::table('absens')
            ->leftJoin('users', 'users.id', '=', 'absens.user_id')
            ->leftJoin('categories', 'categories.id', '=', 'absens.category_id')
            ->select(
                'absens.*',
                'users.name as username',
                'users.email as userEmail',
                'users.userCode as userCode',
                'categories.name as categoryName'
            );

        // Filter pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('absens.hr_status', 'like', "%{$search}%")
                    ->orWhere('absens.status', 'like', "%{$search}%")
                    ->orWhere('users.userCode', 'like', "%{$search}%");
            });
        }

        // Order by priority: pending first, then by date
        $absensi = $query
            ->orderByRaw("CASE WHEN absens.hr_status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('absens.tanggal_awal', 'desc')
            ->cursorPaginate($request->limit ?? 10);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'Data absensi ditemukan.',
            'data' => $absensi
        ]);
    }



    public function show($id)
    {
        $absensi = Absen::with(['user', 'category'])->find($id);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'detail absensi',
            'data' => $absensi
        ], 200);
    }

    public function confirm($id)
    {
        $absensi = Absen::find($id);

        $absensi->update([
            'hr_status' => 'approved'
        ]);
        $absensi->save();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil terkonfirmasi',
            'data' => $absensi
        ], 200);
    }

    public function search($search)
    {
        $absensi = Absen::where(function ($q) use ($search) {
            $q->where('name', 'lile', "%{$search}%");
        })->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->cursorPaginate(10);

        if ($absensi->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $absensi
        ], 200);
    }
}
