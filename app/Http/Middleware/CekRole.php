<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $menuName, $action = 'read'): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $roleId = $user->role_id;
        $action = trim($action);

        $validActions = ['read', 'create', 'update', 'delete'];
        if (!in_array($action, $validActions)) {
            return response()->json(['message' => 'Invalid action.'], 400);
        }

        $permission = DB::table('navigation_menus as m')
            ->join('navigation_groups as ng', 'm.id', '=', 'ng.navigation_menu_id')
            ->where('ng.role_id', $roleId)  
            ->where('m.name', $menuName)
            ->select("ng.{$action}_access as has_access")
            ->first();

        if (!$permission || !$permission->has_access) {
            return response()->json(['message' => 'Forbidden. No access rights.'], 403);
        }

        return $next($request);
    }
}
