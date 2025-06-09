<?php

namespace App\Http\Controllers;

use App\Models\KantorZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KantorZoneController extends Controller
{
    public function getZoneForUser()
    {
        $kantorZone = KantorZone::cursorPaginate(1);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data kantor zone',
            'data' => $kantorZone
        ], 200);
    }
    public function index()
    {
        $kantorZone = KantorZone::cursorPaginate(1);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data kantor zone',
            'data' => $kantorZone
        ], 200);
    }

    public function show($id)
    {
        $kantorZone = KantorZone::find($id);
        return response()->json([
            'statusCode' => 200,
            'msg' => 'detail kantorZone',
            'data' => $kantorZone
        ], 200);
    }

    public function search($search)
    {
        $kantorZone = KantorZone::where(function ($q) use ($search) {
            $q->where('name', 'lile', "%{$search}%");
        })->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->cursorPaginate(10);

        if ($kantorZone->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $kantorZone
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius_meter' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
                'data' => []
            ], 200);
        }

        if (KantorZone::count() >= 1) {
            return response()->json(['message' => 'Zona sudah ada'], 403);
        }

        $input = $validate->validate();
        $kantorZone = KantorZone::create($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil disimpan',
            'data' => $kantorZone
        ], 200);
    }

    public function update(Request $request,  $id)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius_meter' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $kantorZone = KantorZone::find($id);
        $input = $request->all();

        $kantorZone->update($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil diubah',
            'data' => $kantorZone
        ], 200);
    }

    public function destroy($id)
    {
        $kantorZone = KantorZone::find($id);
        $kantorZone->delete();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil dihapus',
            'data' => $kantorZone
        ], 200);
    }
}
