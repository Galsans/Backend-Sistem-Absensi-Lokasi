<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AbsensiUserController extends Controller
{
    public function index(Request $request)
    {
        $auth = Auth::id(); // Ambil ID user yang sedang login
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
            )
            ->where('absens.user_id', $auth); // Filter berdasarkan user login

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('absens.status', 'like', "%{$search}%")
                    ->orWhere('absens.hr_status', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.userCode', 'like', "%{$search}%")
                    ->orWhere('categories.name', 'like', "%{$search}%");
            });
        }

        $absensi = $query
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
        $auth = Auth::id();

        $absensi = Absen::where('id', $id)
            ->where('user_id', $auth)
            ->with(['user', 'category']) // jika ingin relasi ditampilkan
            ->first();

        if (!$absensi) {
            return response()->json([
                'statusCode' => 404,
                'msg' => 'Data absensi tidak ditemukan atau bukan milik Anda',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'statusCode' => 200,
            'msg' => 'Detail absensi personal',
            'data' => $absensi,
        ], 200);
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "user_id" => 'exists:users,id',
            "tanggal_awal" => 'required|date',
            "keterangan" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
                'data' => []
            ], 200);
        }

        // $input = $validate->validate();
        $input = $request->all();
        $input['status'] = 'masuk';
        $absensi = Absen::create($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil disimpan',
            'data' => $absensi
        ], 200);
    }

    public function update(Request $request,  $id)
    {
        $validate = Validator::make($request->all(), [
            "user_id" => 'required|exists:users,id',
            "category_id" => 'required|exists:categories,id',
            "tanggal_akhir" => 'required|date',
            "tanggal_awal" => 'required|date',
            "keterangan" => 'required',
            "bukti" => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $absensi = Absen::find($id);
        $auth = Auth::id();

        if ($auth !== $absensi->user_id) {
            return response()->json([
                'statusCode' => 403,
                'msg' => 'tidak dapat mengubah data orang lain',
            ], 403);
        }

        $input = $validate->validated();

        $absensi->update($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil diubah',
            'data' => $absensi
        ], 200);
    }

    public function izin(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            "user_id" => 'required|exists:users,id',
            "category_id" => 'required|exists:categories,id',
            "tanggal_akhir" => 'required|date',
            "tanggal_awal" => 'required|date',
            "keterangan" => 'required',
            "bukti" => 'mimes:png,jpg,jpeg|nullable',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $imgUrl = null;

        if ($request->file('bukti')) {
            $img = $request->file('bukti')->store('bukti', 'public');
            $imgUrl = Storage::url($img);
        }

        $input = $request->all();
        $category = Category::find($id);
        $auth = Auth::id();

        $input['status'] = 'izin';
        $input['category_id'] = $category->id;
        $input['user_id'] = $auth;
        $input['bukti'] = $imgUrl;

        $izin = Absen::create($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'berhasil menyimpan data',
            'data' => $izin
        ], 200);
    }

    public function listIzin(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('isBukti', 'LIKE', '%' . $request->search . '%');
        }

        $category = $query->orderBy('id')->cursorPaginate($request->limit ?? 10);


        return response()->json([
            'statusCode' => 200,
            'msg' => 'data category',
            'data' => $category
        ], 200);
    }

    public function searchIzin($search)
    {
        $category = Category::where(function ($q) use ($search) {
            $q->where('name', 'lile', "%{$search}%");
        })->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->cursorPaginate(10);

        if ($category->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $category
        ], 200);
    }
    public function destroy($id)
    {
        $absensi = Absen::find($id);
        $absensi->delete();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil dihapus',
            'data' => $absensi
        ], 200);
    }
}
