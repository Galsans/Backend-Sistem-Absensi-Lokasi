<?php

namespace App\Http\Controllers;

use App\Models\NavigationMenu;
use Illuminate\Http\Request;

class NavigationMenuController extends Controller
{
    public function index()
    {
        $navigationMenu = NavigationMenu::cursorPaginate(10);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data navigationMenu',
            'data' => $navigationMenu
        ], 200);
    }

    public function show($id)
    {
        $navigationMenu = NavigationMenu::find($id);
        return response()->json([
            'statusCode' => 200,
            'msg' => 'detail navigationMenu',
            'data' => $navigationMenu
        ], 200);
    }

    public function search($search)
    {
        $navigationMenu = NavigationMenu::where(function ($q) use ($search) {
            $q->where('name', 'lile', "%{$search}%");
        })->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->cursorPaginate(10);

        if ($navigationMenu->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $navigationMenu
        ], 200);
    }
}
