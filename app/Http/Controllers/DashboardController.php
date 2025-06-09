<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function profile()
    {
        $user = Auth::id();
        $data = User::with('role')->where('id', $user)->get();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'profile',
            'data' => $data
        ], 200);
    }
    public function dashboardAdmin()
    {
        return response()->json([
            'msg' => 'dashboardAdmin'
        ], 200);
    }
}
