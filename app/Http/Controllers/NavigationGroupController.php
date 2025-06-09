<?php

namespace App\Http\Controllers;

use App\Models\NavigationGroup;
use App\Models\NavigationMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NavigationGroupController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $query = DB::table('navigation_groups as ng')
            ->leftJoin('roles as r', 'r.id', '=', 'ng.role_id')
            ->leftJoin('navigation_menus as nm', 'nm.id', '=', 'ng.navigation_menu_id')
            ->select(
                'ng.*',
                'r.name as roleName',
                'nm.name as navigationName'
            );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('r.name', 'like', "%{$search}%")
                    ->orWhere('nm.name', 'like', "%{$search}%");
            });
        }

        $navigationGroup = $query
            ->orderBy('ng.created_at', 'desc')
            ->orderBy('ng.id', 'asc')
            ->cursorPaginate(2);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data navigation group',
            'data' => $navigationGroup
        ]);
    }


    public function show($id)
    {
        $navigationGroup = NavigationGroup::with('role', 'navigationMenu')->find($id);

        if ($navigationGroup == null) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ada',
                'data' => []
            ], 200);
        }

        return response()->json([
            'statusCode' => 200,
            'msg' => 'detail navigation group',
            'data' => $navigationGroup
        ], 200);
    }

    public function search($search)
    {
        $navigationGroup = DB::table('navigation_groups as ng')
            ->leftJoin('roles', 'roles.id', '=', 'ng.role_id')
            ->leftJoin('navigation_menus', 'navigation_menus.id', '=', 'ng.navigation_menu_id')
            ->select(
                'ng.*',
                'roles.name as roleName',
                'navigation_menus.name as navigationName',
            )
            ->where(function ($q) use ($search) {
                $q->where('roles.name', 'like', "%{$search}%")
                    ->orWhere('navigation_menus.name', 'like', "%{$search}%");
            })
            ->orderBy('ng.created_at', 'DESC')
            ->orderBy('ng.id', 'ASC')
            ->cursorPaginate(10);

        if ($navigationGroup->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $navigationGroup
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "role_id" => 'required',
            "navigation_menu_id" => 'required',
            "create_access" => 'required',
            "read_access" => 'required',
            "update_access" => 'required',
            "delete_access" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $input = $validate->validated();

        // Cek apakah data dengan role_id dan invent_navigation_menus_id sudah ada
        $exists = NavigationGroup::where('role_id', $input['role_id'])
            ->where('navigation_menu_id', $input['navigation_menu_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'statusCode' => 409,
                'msg' => 'Data dengan kombinasi role dan menu tersebut sudah ada.',
                'data' => []
            ], 409);
        }


        $navigationGroup = NavigationGroup::create($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil disimpan',
            'data' => $navigationGroup
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            "role_id" => 'required',
            "navigation_menu_id" => 'required',
            "create_access" => 'required',
            "read_access" => 'required',
            "update_access" => 'required',
            "delete_access" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $navigationGroup = NavigationGroup::find($id);

        $input = $validate->validated();

        // Cek apakah data dengan role_id dan invent_navigation_menus_id sudah ada
        // Cek apakah kombinasi role_id dan navigation_menu_id sudah ada, KECUALI untuk data ini sendiri
        $exists = NavigationGroup::where('role_id', $input['role_id'])
            ->where('navigation_menu_id', $input['navigation_menu_id'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'statusCode' => 409,
                'msg' => 'Data dengan kombinasi role dan menu tersebut sudah ada.',
                'data' => []
            ], 409);
        }

        $navigationGroup->update($input);
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil diubah',
            'data' => $navigationGroup
        ], 200);
    }

    public function destroy($id)
    {
        $navigationGroup = NavigationGroup::find($id);

        $navigationGroup->delete();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil dihapus',
            'data' => $navigationGroup
        ], 200);
    }
}
