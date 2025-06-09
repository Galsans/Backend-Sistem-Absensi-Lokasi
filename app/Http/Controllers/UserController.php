<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserMenu()
    {
        $user = Auth::user();

        $menus = DB::table('navigation_menus as m')
            ->join('navigation_groups as ng', 'm.id', '=', 'ng.navigation_menu_id')
            ->where('ng.role_id', $user->role_id)
            ->where('ng.read_access', true)
            ->orderBy('m.sort_order') // Jika kamu punya field untuk urutan
            ->select('m.name') // pastikan kolom ini tersedia
            ->get();

        return response()->json($menus);
    }

    // public function index()
    // {
    //     $user = User::with('role')->cursorPaginate(10);

    //     if ($user->isEmpty()) {
    //         return response()->json([
    //             'statusCode' => 200,
    //             'msg' => 'data belum ada',
    //             'data' => []
    //         ], 200);
    //     }
    //     return response()->json([
    //         'statusCode' => 200,
    //         'msg' => 'data user',
    //         'data' => $user
    //     ], 200);

    //     $query = Role::query();

    //     if ($request->has('search') && !empty($request->search)) {
    //         $query->where('name', 'LIKE', '%' . $request->search . '%');
    //     }

    //     $role = $query->orderBy('id')->cursorPaginate($request->limit ?? 10);


    //     return response()->json([
    //         'statusCode' => 200,
    //         'msg' => 'data role',
    //         'data' => $role
    //     ], 200);
    // }

    //     public function index(Request $request)
    // {
    //     $search = $request->search;

    //     $query = DB::table('navigation_groups as ng')
    //         ->leftJoin('roles as r', 'r.id', '=', 'ng.role_id')
    //         ->leftJoin('navigation_menus as nm', 'nm.id', '=', 'ng.navigation_menu_id')
    //         ->select(
    //             'ng.*',
    //             'r.name as roleName',
    //             'nm.name as navigationName'
    //         );

    //     if (!empty($search)) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('r.name', 'like', "%{$search}%")
    //                 ->orWhere('nm.name', 'like', "%{$search}%");
    //         });
    //     }

    //     $navigationGroup = $query
    //         ->orderBy('ng.created_at', 'desc')
    //         ->orderBy('ng.id', 'asc')
    //         ->cursorPaginate($request->limit ?? 10);

    //     return response()->json([
    //         'statusCode' => 200,
    //         'msg' => 'data navigation group',
    //         'data' => $navigationGroup
    //     ]);
    // }

    public function index(Request $request)
    {
        $search = $request->search;

        $query = DB::table('users')
            ->leftJoin('roles as r', 'r.id', '=', 'users.role_id')
            ->select(
                'users.*',
                'r.name as roleName',
            );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('r.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.userCode', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        $user = $query
            ->orderBy('users.created_at', 'desc')
            ->orderBy('users.id', 'asc')
            ->cursorPaginate($request->limit ?? 10);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data user',
            'data' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::with('role')->find($id);

        if ($user == null) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data belum ada',
                'data' => []
            ], 200);
        }

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data detail user',
            'data' => $user
        ], 200);
    }
}
